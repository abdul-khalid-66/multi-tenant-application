<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $variants = ProductVariant::with('product')
            ->latest()
            ->paginate(10);
        $products = Product::get();

        return view('app.product.product_variants.index  ', compact('variants','products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('app.product.product_variants.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:100',
            'sku' => 'required|string|max:100|unique:product_variants',
            'price_sale' => 'required|numeric|min:0',
            'price_cost' => 'required|numeric|min:0',
            'status' => 'required|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
        ]);

        ProductVariant::create($validated);

        return redirect()->route('product-variants.index')->with('success', 'Variant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductVariant $productVariant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductVariant $productVariant)
    {
        $products = Product::all();
        return view('app.product.product_variants.edit', compact('productVariant', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
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
            'weight' => 'nullable|numeric|min:0',
        ]);

        $productVariant->update($validated);

        return redirect()->route('product-variants.index')->with('success', 'Variant updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariant $productVariant)
    {
        $productVariant->delete();
        return redirect()->route('product-variants.index')->with('success', 'Variant deleted successfully.');
    }
}
