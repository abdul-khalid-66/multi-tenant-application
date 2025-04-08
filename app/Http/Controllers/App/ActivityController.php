<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\InventoryLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = $this->getAllActivities();
        
        // Manually paginate the collection
        $page = $request->get('page', 1);
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        
        $paginatedItems = $activities->slice($offset, $perPage)->all();
        
        $activities = new LengthAwarePaginator(
            $paginatedItems,
            $activities->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        return view('app.sales.activities.index', compact('activities'));
    }

    protected function getAllActivities()
    {
        $activities = new Collection();

        // Sales Activities
        $activities = $activities->merge(
            Sale::with('customer')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($sale) {
                    return (object) [
                        'type' => 'sale',
                        'icon' => 'check',
                        'color' => 'green',
                        'title' => 'Sale completed',
                        'description' => 'Invoice #'.$sale->invoice_no.' - $'.number_format($sale->total_amount, 2),
                        'created_at' => $sale->created_at,
                        'user' => $sale->customer->name ?? 'System'
                    ];
                })
        );

        // Product Activities
        $activities = $activities->merge(
            Product::orderBy('created_at', 'desc')
                ->get()
                ->map(function ($product) {
                    return (object) [
                        'type' => 'product',
                        'icon' => 'plus',
                        'color' => 'blue',
                        'title' => 'Product added',
                        'description' => $product->name,
                        'created_at' => $product->created_at,
                        'user' => 'Admin'
                    ];
                })
        );

        // Return Activities
        $activities = $activities->merge(
            ProductReturn::with(['sale', 'customer'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($return) {
                    return (object) [
                        'type' => 'return',
                        'icon' => 'x',
                        'color' => 'red',
                        'title' => 'Return processed',
                        'description' => 'For Sale #'.($return->sale->invoice_no ?? 'N/A'),
                        'created_at' => $return->created_at,
                        'user' => $return->customer->name ?? 'Customer'
                    ];
                })
        );

        // Inventory Activities
        $activities = $activities->merge(
            InventoryLog::with('product')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($log) {
                    return (object) [
                        'type' => 'inventory',
                        'icon' => 'pencil',
                        'color' => 'purple',
                        'title' => 'Stock adjusted',
                        'description' => ($log->product->name ?? 'Product').' ('.$log->old_stock.' → '.$log->new_stock.')',
                        'created_at' => $log->created_at,
                        'user' => 'System'
                    ];
                })
        );
    
        return $activities->sortByDesc('created_at')->values();
    }
}