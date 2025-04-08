<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\ProductReturn;
use App\Models\ReturnDetail;
use App\Models\ProductVariant;
use App\Models\CashInHandDetail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReturnController extends Controller
{

    public function index()
    {
        $returns = ProductReturn::with(['customer', 'sale'])
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('customer_id'), function($query, $customerId) {
                return $query->where('customer_id', $customerId);
            })
            ->when(request('start_date'), function($query, $startDate) {
                return $query->whereDate('return_date', '>=', $startDate);
            })
            ->when(request('end_date'), function($query, $endDate) {
                return $query->whereDate('return_date', '<=', $endDate);
            })
            ->latest()
            ->paginate(10);

        $customers = Customer::orderBy('name')->get();

        return view('app.sales.returns.index', compact('returns', 'customers'));
    }

    public function create()
    {
        $sales = Sale::with(['customer', 'saleDetails' => function($query) {
                $query->with(['product', 'variant', 'returnDetails'])
                    ->whereHas('product') // Ensure product exists
                    ->where(function($q) {
                        $q->whereDoesntHave('returnDetails')
                          ->orWhereHas('returnDetails', function($q) {
                              $q->selectRaw('product_id, variant_id, sum(quantity_returned) as total_returned')
                                ->groupBy('product_id', 'variant_id')
                                ->havingRaw('sum(quantity_returned) < sale_details.quantity');
                          });
                    });
            }])
            ->where('payment_status', 'paid')
            ->whereHas('saleDetails', function($query) {
                $query->whereHas('product')
                    ->where(function($q) {
                        $q->whereDoesntHave('returnDetails')
                          ->orWhereHas('returnDetails', function($q) {
                              $q->selectRaw('product_id, variant_id, sum(quantity_returned) as total_returned')
                                ->groupBy('product_id', 'variant_id')
                                ->havingRaw('sum(quantity_returned) < sale_details.quantity');
                          });
                    });
            })
            ->latest()
            ->get();

        return view('app.sales.returns.create', compact('sales'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'sale_id' => 'required|exists:sales,id',
                'reason' => 'required|string',
                'items' => 'required|array|min:1',
                'items.*.sale_detail_id' => 'required|exists:sale_details,id',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => [
                    'required',
                    'integer',
                    'min:1',
                    function ($attribute, $value, $fail) use ($request) {
                        $saleDetailId = str_replace(['items.', '.quantity'], '', $attribute);
                        $saleDetail = \App\Models\SaleDetail::find($request->input("items.$saleDetailId.sale_detail_id"));
                        
                        if ($saleDetail) {
                            $returnedQty = ReturnDetail::whereHas('return', function($query) use ($saleDetail) {
                                    $query->where('sale_id', $saleDetail->sale_id);
                                })
                                ->where('product_id', $saleDetail->product_id)
                                ->where('variant_id', $saleDetail->variant_id)
                                ->sum('quantity_returned');
                            
                            $available = $saleDetail->quantity - $returnedQty;
                            
                            if ($value > $available) {
                                $fail("Cannot return more than $available items for this product.");
                            }
                        }
                    }
                ]
            ]);

            $sale = Sale::with('saleDetails')->find($validated['sale_id']);
            $totalRefund = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                $saleDetail = $sale->saleDetails()->find($item['sale_detail_id']);

                // Get variant_id from saleDetail if not in request
                $variantId = $item['variant_id'] ?? $saleDetail->variant_id;

                // Calculate total quantity sold for this product/variant in the sale
                $totalSoldQuery = $sale->saleDetails()
                    ->where('product_id', $item['product_id']);

                if ($variantId) {
                    $totalSoldQuery->where('variant_id', $variantId);
                } else {
                    $totalSoldQuery->whereNull('variant_id');
                }

                $totalSoldInSale = $totalSoldQuery->sum('quantity');

                // Calculate total already returned for this product/variant in the sale
                $alreadyReturnedQuery = ReturnDetail::whereHas('return', function ($query) use ($sale) {
                    $query->where('sale_id', $sale->id);
                })
                    ->where('product_id', $item['product_id']);

                if ($variantId) {
                    $alreadyReturnedQuery->where('variant_id', $variantId);
                } else {
                    $alreadyReturnedQuery->whereNull('variant_id');
                }

                $alreadyReturned = $alreadyReturnedQuery->sum('quantity_returned');

                $availableQty = $totalSoldInSale - $alreadyReturned;

                if ($item['quantity'] > $availableQty) {
                    throw new \Exception("Cannot return more than available quantity for this item. Available: {$availableQty}");
                }

                // Calculate proportional tax and discount
                $totalItemsInSale = $sale->saleDetails->sum('quantity');
                $taxPerUnit = $totalItemsInSale > 0 ? $sale->tax / $totalItemsInSale : 0;
                $discountPerUnit = $totalItemsInSale > 0 ? $sale->discount / $totalItemsInSale : 0;

                $refundPerUnit = $saleDetail->sell_price + $taxPerUnit - $discountPerUnit;
                $totalItemRefund = $refundPerUnit * $item['quantity'];
                $totalRefund += $totalItemRefund;

                $items[] = [
                    'product_id' => $item['product_id'],
                    'variant_id' => $variantId,
                    'quantity_returned' => $item['quantity'],
                    'refund_amount_per_unit' => $refundPerUnit,
                    'total_refund_amount' => $totalItemRefund
                ];
            }

            $return = ProductReturn::create([
                'sale_id' => $validated['sale_id'],
                'customer_id' => $sale->customer_id,
                'return_date' => now(),
                'reason' => $validated['reason'],
                'status' => 'pending',
                'total_refund_amount' => $totalRefund
            ]);

            $return->returnDetails()->createMany($items);

            DB::commit();

            return redirect()->route('returns.index')
                ->with('success', 'Return request created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create return: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(ProductReturn $return)
    {
        $return->load(['sale', 'customer', 'returnDetails.product', 'returnDetails.variant']);
        return view('app.sales.returns.show', compact('return'));
    }

    public function edit(ProductReturn $return)
    {
        if ($return->status !== 'pending') {
            return redirect()->route('returns.index')
                ->with('error', 'Only pending returns can be edited');
        }

        $return->load([
            'sale.saleDetails', // Load sale with its details
            'returnDetails.product', 
            'returnDetails.variant',
            'returnDetails.return' // Load the parent return
        ]);
        
        return view('app.sales.returns.edit', compact('return'));
    }
    public function approve(Request $request, ProductReturn $return)
    {
        DB::beginTransaction();

        try {
            // Validate approval
            if ($return->status !== 'pending') {
                throw new \Exception('Only pending returns can be approved');
            }

            // Process refund
            $return->update(['status' => 'approved']);

            // Restock items
            foreach ($return->returnDetails as $detail) {
                if ($detail->variant_id) {
                    ProductVariant::find($detail->variant_id)
                        ->increment('stock_quantity', $detail->quantity_returned);
                }
            }

            // Record cash movement
            CashInHandDetail::create([
                'date' => now(),
                'amount' => -$return->total_refund_amount,
                'transaction_type' => 'refund',
                'reference_id' => $return->id
            ]);

            DB::commit();

            return redirect()->route('returns.index')
                ->with('success', 'Return approved and refund processed');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    // public function analytics()
    // {
    //     $analytics = ProductReturn::select([
    //         DB::raw('reason as return_reason'),
    //         DB::raw('count(*) as count'),
    //         DB::raw('sum(total_refund_amount) as total_refund')
    //     ])
    //         ->groupBy('reason')
    //         ->orderBy('count', 'desc')
    //         ->get();

    //     return view('app.sales.returns.analytics', compact('analytics'));
    // }


    public function approval()
    {
        $pendingReturns = ProductReturn::with(['customer', 'sale'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('app.sales.returns.approval', compact('pendingReturns'));
    }

    public function analytics()
    {
        // Reason statistics using Eloquent
        $reasonStats = ProductReturn::query()
            ->select('reason')
            ->selectRaw('count(*) as count')
            ->selectRaw('sum(total_refund_amount) as total_amount')
            ->selectRaw('avg(total_refund_amount) as avg_amount')
            ->groupBy('reason')
            ->orderByDesc('count')
            ->get();

        $totalReturns = ProductReturn::count();

        // Monthly trend using Eloquent
        $monthlyTrend = ProductReturn::query()
            ->selectRaw("DATE_FORMAT(return_date, '%Y-%m') as month")
            ->selectRaw('count(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Top returned products using Eloquent relationships
        $topProducts = ReturnDetail::with(['product', 'variant'])
            ->select(['product_id', 'variant_id'])
            ->selectRaw('count(*) as return_count')
            ->selectRaw('sum(quantity_returned) as total_quantity')
            ->groupBy(['product_id', 'variant_id'])
            ->orderByDesc('return_count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant?->name,
                    'return_count' => $item->return_count,
                    'total_quantity' => $item->total_quantity
                ];
            });

        return view('app.sales.returns.analytics', compact(
            'reasonStats',
            'totalReturns',
            'monthlyTrend',
            'topProducts'
        ));
    }
}
