<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                    $variant = ProductVariant::find($item['variant_id']);
                    $variant->decrement('stock_quantity', $item['quantity']);
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
        return view('app.sales.show', compact('sale'));
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
}
