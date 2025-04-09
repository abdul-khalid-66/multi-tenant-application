<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
   {
       $suppliers = Supplier::paginate(10);
       return view('app.suppliers.index', compact('suppliers'));
   }

    /**
     * Show the form for creating a new resource.
     */
   public function create()
   {
       return view('app.suppliers.create');
   }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
   {
       $validated = $request->validate([
           'name' => 'required|string|max:255',
           'contact' => 'required|string|max:100',
           'address' => 'required|string',
       ]);

       Supplier::create($validated);
       return redirect()->route('suppliers.index')
                        ->with('success', 'Supplier created successfully.');
   }

    /**
     * Display the specified resource.
     */
   public function show(Supplier $supplier)
   {
       return view('app.suppliers.show', compact('supplier'));
   }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(Supplier $supplier)
   {
       return view('app.suppliers.edit', compact('supplier'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, Supplier $supplier)
   {
       $validated = $request->validate([
           'name' => 'required|string|max:255',
           'contact' => 'required|string|max:100',
           'address' => 'required|string',
       ]);

       $supplier->update($validated);

       return redirect()->route('suppliers.index')
                        ->with('success', 'Supplier updated successfully.');
   }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Supplier $supplier)
   {
       $supplier->delete();
       return redirect()->route('suppliers.index')
                        ->with('success', 'Supplier deleted successfully.');
   }

   /**
    * Show supplier performance metrics
    */
   public function performance(Supplier $supplier)
   {
        $metrics = [
            'product_count' => $supplier->products()->count(),
            'inventory_changes' => $supplier->inventoryLogs()->count(),
            'current_stock_value' => $supplier->products()->with('variants')
                ->get()
                ->sum(function($product) {
                    return $product->variants->sum(function($variant) {
                        return $variant->stock_quantity * $variant->price_cost;
                    });
                }),
        ];
       return view('app.suppliers.performance', compact('supplier', 'metrics'));
   }

   /**
    * Show products supplied by this supplier
    */
   public function products(Supplier $supplier)
   {
       $products = $supplier->products()->with('variants')->paginate(10);
       return view('app.suppliers.products', compact('supplier', 'products'));
   }
}
