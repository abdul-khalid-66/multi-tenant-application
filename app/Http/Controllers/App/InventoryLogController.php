<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class InventoryLogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //
    }
    public function index(Request $request)
    {
        $query = InventoryLog::with(['product', 'variant'])
            ->latest();

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('variant_id')) {
            $query->where('variant_id', $request->variant_id);
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('date', [
                $request->date_from,
                $request->date_to
            ]);
        }

        $logs = $query->paginate(10);
        $products = Product::all();
        $variants = ProductVariant::all();

        return view('app.product.inventory_logs.index', compact('logs', 'products', 'variants'));
    }
}
