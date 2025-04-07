<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\ProductReturn;
use App\Models\ReturnDetail;
use App\Models\ProductVariant;
use App\Models\CashInHandDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReturnController extends Controller
{
    public function index()
    {
        $returns = ProductReturn::with(['sale', 'customer', 'returnDetails'])
            ->latest()
            ->paginate(10);

        return view('app.sales.returns.index', compact('returns'));
    }

    public function create()
    {
        $sales = Sale::with(['saleDetails.product', 'saleDetails.variant'])
            ->where('payment_status', 'paid')
            ->get();

        return view('app.sales.returns.create', compact('sales'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'sale_id' => 'required|exists:sales,id',
                'return_date' => 'required|date',
                'reason' => 'required|string',
                'items' => 'required|array|min:1',
                'items.*.sale_detail_id' => 'required|exists:sale_details,id',
                'items.*.quantity' => 'required|integer|min:1'
            ]);

            $sale = Sale::find($validated['sale_id']);
            $totalRefund = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                $saleDetail = $sale->saleDetails()->find($item['sale_detail_id']);

                // Verify available quantity
                $returnedQty = $saleDetail->returnDetails()->sum('quantity_returned');
                $availableQty = $saleDetail->quantity - $returnedQty;

                if ($item['quantity'] > $availableQty) {
                    throw new \Exception("Cannot return more than available quantity for item");
                }

                // Calculate proportional tax and discount
                $taxPerUnit = $sale->tax / $sale->saleDetails->sum('quantity');
                $discountPerUnit = $sale->discount / $sale->saleDetails->sum('quantity');

                $refundPerUnit = $saleDetail->sell_price + $taxPerUnit - $discountPerUnit;
                $totalItemRefund = $refundPerUnit * $item['quantity'];
                $totalRefund += $totalItemRefund;

                $items[] = [
                    'product_id' => $saleDetail->product_id,
                    'variant_id' => $saleDetail->variant_id,
                    'quantity_returned' => $item['quantity'],
                    'refund_amount_per_unit' => $refundPerUnit,
                    'total_refund_amount' => $totalItemRefund
                ];
            }

            $return = ProductReturn::create([
                'sale_id' => $validated['sale_id'],
                'customer_id' => $sale->customer_id,
                'return_date' => $validated['return_date'],
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

        $return->load(['sale', 'returnDetails.product', 'returnDetails.variant']);
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

    public function analytics()
    {
        $analytics = ProductReturn::select([
            DB::raw('reason as return_reason'),
            DB::raw('count(*) as count'),
            DB::raw('sum(total_refund_amount) as total_refund')
        ])
            ->groupBy('reason')
            ->orderBy('count', 'desc')
            ->get();

        return view('app.sales.returns.analytics', compact('analytics'));
    }
}
