<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index()
    {
        $variants = ProductVariant::with('product')
            ->latest()
            ->paginate(10);

        return view('product-variants.index', compact('variants'));
    }

    public function create()
    {
        $products = Product::all();
        return view('product-variants.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:100',
            'sku' => 'required|string|max:100|unique:product_variants,sku',
            'price_sale' => 'required|numeric|min:0',
            'price_cost' => 'required|numeric|min:0',
            'status' => 'required|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0'
        ]);

        ProductVariant::create($validated);

        return redirect()->route('product-variants.index')
            ->with('success', 'Product variant created successfully.');
    }

    public function edit(ProductVariant $productVariant)
    {
        $products = Product::all();
        return view('product-variants.edit', compact('productVariant', 'products'));
    }

    public function update(Request $request, ProductVariant $productVariant)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:100',
            'sku' => 'required|string|max:100|unique:product_variants,sku,' . $productVariant->id,
            'price_sale' => 'required|numeric|min:0',
            'price_cost' => 'required|numeric|min:0',
            'status' => 'required|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0'
        ]);

        $productVariant->update($validated);

        return redirect()->route('product-variants.index')
            ->with('success', 'Product variant updated successfully.');
    }

    public function destroy(ProductVariant $productVariant)
    {
        $productVariant->delete();

        return redirect()->route('product-variants.index')
            ->with('success', 'Product variant deleted successfully.');
    }
}
