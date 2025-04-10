<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\CashInHandDetail;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ReturnDetail;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'saleDetails'])
            ->latest()
            ->paginate(10);

        return view('app.sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('variants')->get();

        return view('app.sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {


        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'date' => 'required|date',
                'payment_status' => 'required|in:paid,pending,partial',
                'payment_method' => 'required|in:cash,credit_card,debit_card,transfer',
                'discount' => 'nullable|numeric|min:0',
                'tax' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.variant_id' => 'nullable|exists:product_variants,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.sell_price' => 'required|numeric|min:0',
                'items.*.unit' => 'nullable|string',
                'items.*.note' => 'nullable|string'
            ]);

            // Generate invoice number
            $invoiceNo = 'INV-' . Str::upper(Str::random(6)) . date('Ymd');

            // Calculate totals
            $totalAmount = 0;
            $totalCost = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $variant = $item['variant_id'] ? ProductVariant::find($item['variant_id']) : null;

                $subtotal = $item['quantity'] * $item['sell_price'];
                $cost = $item['quantity'] * ($variant ? $variant->price_cost : $product->cost_price);

                $totalAmount += $subtotal;
                $totalCost += $cost;

                $items[] = [
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'cost_price' => $variant ? $variant->price_cost : $product->cost_price,
                    'sell_price' => $item['sell_price'],
                    'unit' => $item['unit'] ?? 'pcs',
                    'line_item_note' => $item['note'] ?? null,
                    'total_price' => $subtotal
                ];
            }

            // Apply discount and tax
            $discountAmount = $validated['discount'] ?? 0;
            $taxAmount = $validated['tax'] ?? 0;
            $totalAmount = $totalAmount - $discountAmount + $taxAmount;

            // Create sale
            $sale = Sale::create([
                'invoice_no' => $invoiceNo,
                'total_amount' => $totalAmount,
                'cost_price' => $totalCost,
                'date' => $validated['date'],
                'customer_id' => $validated['customer_id'],
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'],
                'discount' => $discountAmount,
                'tax' => $taxAmount,
                'notes' => $validated['notes'] ?? null,
                'product_id' => $validated['items'][1]['product_id'], // Add this line
                'variant_id' => $validated['items'][1]['variant_id'] ?? null // Add this if needed
            ]);

            // Create sale saleDetails
            foreach ($items as $item) {
                $sale->saleDetails()->create($item);

                // Update stock if variant exists
                if ($item['variant_id']) {
                    // $variant = ProductVariant::find($item['variant_id']);
                    // $variant->decrement('stock_quantity', $item['quantity']);
                } else {
                    // Update product stock if no variants
                    $product = Product::find($item['product_id']);
                    // Assuming products without variants track stock at product level
                    // You may need to adjust this based on your inventory logic
                }
            }

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Sale created successfully. Invoice #' . $invoiceNo);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create sale: ' . $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'saleDetails.product', 'saleDetails.variant']);
        $totalQuantity   = $sale->saleDetails->sum('quantity');

        return view('app.sales.show', compact('sale','totalQuantity'));
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();

        try {
            // Restore stock for each item
            foreach ($sale->saleDetails as $detail) {
                if ($detail->variant_id) {
                    $variant = ProductVariant::find($detail->variant_id);
                    $variant->increment('stock_quantity', $detail->quantity);
                }
            }

            $sale->delete();
            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Sale deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete sale: ' . $e->getMessage());
        }
    }

    public function printInvoice(Sale $sale)
    {
        $data = [
            'sale' => $sale->load(['customer', 'saleDetails.product', 'saleDetails.variant']),
            'company' => [
                'name' => config('app.name'),
                'address' => '123 Business Street, City, State 10001',
                'phone' => '(123) 456-7890',
                'email' => 'info@yourbusiness.com',
                'logo' => asset('images/product.png') // Use asset() for web path
            ]
        ];

        return view('app.sales.sale_invoice_print', $data);
    }

    public function generateInvoicePDF(Sale $sale)
    {
        $data = [
            'sale' => $sale,
            'company' => [
                'name' => 'Your Business Name',
                'address' => '123 Business Street, City, State 10001',
                'phone' => '(123) 456-7890',
                'email' => 'info@yourbusiness.com'
            ]
        ];

        $pdf = PDF::loadView('app.sales.sale_invoice_pdf', $data);

        return $pdf->download('invoice-' . $sale->invoice_no . '.pdf');
    }

    public function updatePayment(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);
        
        $request->validate([
            'payment_status' => 'required|in:paid,pending,partial',
            'amount_paid' => 'required_if:payment_status,partial|numeric|min:0|max:'.($sale->total_amount - ($sale->amount_paid ?? 0)),
            'payment_method' => 'required|in:cash,credit_card,debit_card,transfer'
        ]);
        
        // Initialize amount_paid if null
        $currentAmountPaid = $sale->amount_paid ?? 0;
        
        // Update payment status and amount
        if ($request->payment_status == 'partial') {
            $newAmountPaid = $currentAmountPaid + $request->amount_paid;
            
            // Check if full amount paid
            if ($newAmountPaid >= $sale->total_amount) {
                $sale->payment_status = 'paid';
                $sale->amount_paid = $sale->total_amount;
            } else {
                $sale->payment_status = 'partial';
                $sale->amount_paid = $newAmountPaid;
            }
        } 
        elseif ($request->payment_status == 'paid') {
            $sale->amount_paid = $sale->total_amount;
            $sale->payment_status = 'paid';
        }
        else {
            $sale->amount_paid = 0;
            $sale->payment_status = 'pending';
        }
        
        $sale->payment_method = $request->payment_method;
        $sale->save();
        
        // Cash in hand update if payment received
        if ($request->payment_status == 'paid' || $request->payment_status == 'partial') {
            $paymentAmount = ($request->payment_status == 'paid') 
                ? ($sale->total_amount - $currentAmountPaid)
                : $request->amount_paid;
                
            CashInHandDetail::create([
                'date' => now(),
                'amount' => $paymentAmount,
                'transaction_type' => 'sale_payment',
                'reference_id' => $sale->id,
                'payment_method' => $request->payment_method
            ]);
        }
        
        return redirect()->back()->with('success', 'Payment status updated successfully');
    }

    public function getSaleItems(Sale $sale)
    {
        try {
            $items = $sale->saleDetails()->with(['product', 'variant'])
                ->get()
                ->map(function ($detail) use ($sale) {
                    $returnedQty = ReturnDetail::whereHas('return', function($query) use ($sale) {
                            $query->where('sale_id', $sale->id);
                        })
                        ->where('product_id', $detail->product_id)
                        ->where('variant_id', $detail->variant_id)
                        ->sum('quantity_returned');
    
                    $totalQuantity = $sale->saleDetails->sum('quantity');
                    $taxPerUnit = $totalQuantity > 0 ? $sale->tax / $totalQuantity : 0;
                    $discountPerUnit = $totalQuantity > 0 ? $sale->discount / $totalQuantity : 0;
    
                    return [
                        'id' => $detail->id,
                        'product_id' => $detail->product_id,
                        'variant_id' => $detail->variant_id,
                        'product_name' => $detail->product->name,
                        'variant_name' => $detail->variant?->name,
                        'sell_price' => $detail->sell_price,
                        'quantity' => $detail->quantity,
                        'remaining_quantity' => $detail->quantity - $returnedQty,
                        'tax_per_unit' => $taxPerUnit,
                        'discount_per_unit' => $discountPerUnit
                    ];
                });
    
            return response()->json($items);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load sale items',
                'message' => $e->getMessage()
            ], 500);
        }
    }


// 08/04/2025
    // public function edit(Sale $sale)
    // {
    //     $sale->load(['customer', 'saleDetails.product', 'saleDetails.variant']);

    //     return view('app.sales.edit', [
    //         'sale' => $sale,
    //         'customers' => Customer::all(),
    //         'products' => Product::with('variants')->get()
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // Validate the request data (you can reuse the same validation logic as store)
    //         $validated = $request->validate([
    //             'customer_id' => 'required|exists:customers,id',
    //             'date' => 'required|date',
    //             'payment_status' => 'required|in:paid,pending,partial',
    //             'payment_method' => 'required|in:cash,credit_card,debit_card,transfer',
    //             'discount' => 'nullable|numeric|min:0',
    //             'tax' => 'nullable|numeric|min:0',
    //             'notes' => 'nullable|string',
    //             'items' => 'required|array|min:1',
    //             'items.*.product_id' => 'required|exists:products,id',
    //             'items.*.variant_id' => 'nullable|exists:product_variants,id',
    //             'items.*.quantity' => 'required|integer|min:1',
    //             'items.*.sell_price' => 'required|numeric|min:0',
    //             'items.*.unit' => 'nullable|string',
    //             'items.*.note' => 'nullable|string'
    //         ]);

    //         // Find the sale to update
    //         $sale = Sale::findOrFail($id); // Find the sale or fail if not found

    //         // Calculate totals again based on updated data
    //         $totalAmount = 0;
    //         $totalCost = 0;
    //         $items = [];

    //         foreach ($validated['items'] as $item) {
    //             $product = Product::find($item['product_id']);
    //             $variant = $item['variant_id'] ? ProductVariant::find($item['variant_id']) : null;

    //             $subtotal = $item['quantity'] * $item['sell_price'];
    //             $cost = $item['quantity'] * ($variant ? $variant->price_cost : $product->cost_price);

    //             $totalAmount += $subtotal;
    //             $totalCost += $cost;

    //             $items[] = [
    //                 'product_id' => $item['product_id'],
    //                 'variant_id' => $item['variant_id'],
    //                 'quantity' => $item['quantity'],
    //                 'cost_price' => $variant ? $variant->price_cost : $product->cost_price,
    //                 'sell_price' => $item['sell_price'],
    //                 'unit' => $item['unit'] ?? 'pcs',
    //                 'line_item_note' => $item['note'] ?? null,
    //                 'total_price' => $subtotal
    //             ];
    //         }

    //         // Apply discount and tax
    //         $discountAmount = $validated['discount'] ?? 0;
    //         $taxAmount = $validated['tax'] ?? 0;
    //         $totalAmount = $totalAmount - $discountAmount + $taxAmount;

    //         // Update the sale record
    //         $sale->update([
    //             'total_amount' => $totalAmount,
    //             'cost_price' => $totalCost,
    //             'date' => $validated['date'],
    //             'customer_id' => $validated['customer_id'],
    //             'payment_status' => $validated['payment_status'],
    //             'payment_method' => $validated['payment_method'],
    //             'discount' => $discountAmount,
    //             'tax' => $taxAmount,
    //             'notes' => $validated['notes'] ?? null,
    //         ]);

    //         // Delete existing saleDetails before adding new ones
    //         $sale->saleDetails()->delete(); 

    //         // Add new sale details (items)
    //         foreach ($items as $item) {
    //             $sale->saleDetails()->create($item);

    //             // Update stock if variant exists
    //             if ($item['variant_id']) {
    //                 $variant = ProductVariant::find($item['variant_id']);
    //                 $variant->decrement('stock_quantity', $item['quantity']);
    //             } else {
    //                 // Update product stock if no variants
    //                 $product = Product::find($item['product_id']);
    //                 // You might want to decrement stock at the product level
    //                 // depending on your business logic
    //             }
    //         }

    //         DB::commit();

    //         return redirect()->route('sales.index')
    //             ->with('success', 'Sale updated successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Failed to update sale: ' . $e->getMessage());
    //     }
    // }

}
