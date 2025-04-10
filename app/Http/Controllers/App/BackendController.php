<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Models\ProductReturn;
use App\Models\ReturnRequest;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BackendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Today's date range
        $today      = Carbon::today();
        $yesterday  = Carbon::yesterday();

        // Current week date range
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        // Last week date range for comparison
        $lastWeekStart  = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd    = Carbon::now()->subWeek()->endOfWeek();

        // 1. Revenue Metrics
        $todayRevenue       = Sale::whereDate('created_at', $today)->sum('total_amount');
        $yesterdayRevenue   = Sale::whereDate('created_at', $yesterday)->sum('total_amount');
        $revenueChange      = $yesterdayRevenue > 0 ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 2) : 0;

        // 2. Profit Metrics
        $todayProfit    = $this->calculateDailyProfit($today);
        $lastWeekProfit = $this->calculateWeeklyProfit($lastWeekStart, $lastWeekEnd);
        $profitChange   = $lastWeekProfit > 0 ? round((($todayProfit - $lastWeekProfit) / $lastWeekProfit) * 100, 2) : 0;

        // Change the profit comparison to be daily instead of weekly
        // $todayProfit = $this->calculateDailyProfit($today);
        // $yesterdayProfit = $this->calculateDailyProfit($yesterday);
        // $profitChange = $yesterdayProfit > 0 
        //     ? round((($todayProfit - $yesterdayProfit) / $yesterdayProfit) * 100, 2)
        //     : 0;


        // 3. Inventory Status
        $totalProducts = Product::count();

        // Fixed low stock items query - assuming reorder_level is in products table
        $lowStockItems = ProductVariant::join('products', 'product_variants.product_id', '=', 'products.id')->whereColumn('product_variants.stock_quantity', '<', 'products.reorder_level')->count();

        // 4. Pending Tasks
        $pendingReturns = ProductReturn::where('status', 'pending')->count();
        $pendingTasks   = $pendingReturns; 

        // 5. Sales Performance Chart Data - Fixed GROUP BY issue
        $salesData = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )->whereBetween('created_at', [$startOfWeek, $endOfWeek])->groupBy(DB::raw('DATE(created_at)'))->orderBy('date')->get();

        // Format for chart (fill in missing dates with 0)
        $formattedSalesData = [];
        $currentDate = clone $startOfWeek;

        while ($currentDate <= $endOfWeek) {
            $dateString = $currentDate->format('Y-m-d');
            $sale = $salesData->firstWhere('date', $dateString);

            $formattedSalesData[] = [
                'date' => $currentDate->format('D M j'),
                'total' => $sale ? $sale->total : 0,
            ];

            $currentDate->addDay();
        }

        // 6. Recent Activities
        $recentActivities = $this->getRecentActivities();

        // 7. Low Stock Items - fixed query with join
        $lowStockProducts = ProductVariant::with(['product'])
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->whereColumn('product_variants.stock_quantity', '<', 'products.reorder_level')
            ->select('product_variants.*')
            ->orderBy('product_variants.stock_quantity', 'asc')
            ->take(3)
            ->get();

        // 8. Recent Sales
        $recentSales = Sale::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // 9. Pending Returns
        $pendingReturnsList = ProductReturn::with(['sale', 'customer'])
            ->whereIn('status', ['pending', 'processing'])
            ->orderBy('return_date', 'desc')
            ->take(3)
            ->get();

        return view('app.dashboard', [
            // Quick Stats
            'todayRevenue' => $todayRevenue,
            'revenueChange' => $revenueChange,
            'todayProfit' => $todayProfit,
            'profitChange' => $profitChange,
            'totalProducts' => $totalProducts,
            'lowStockItems' => $lowStockItems,
            'pendingTasks' => $pendingTasks,

            // Chart Data
            'salesData' => $formattedSalesData,

            // Activity Data
            'recentActivities' => $recentActivities,
            'lowStockProducts' => $lowStockProducts,
            'recentSales' => $recentSales,
            'pendingReturnsList' => $pendingReturnsList,
        ]);
    }

    protected function calculateDailyProfit($date)
    {
        return Sale::whereDate('created_at', $date)
            ->sum(DB::raw('total_amount - cost_price'));
    }

    protected function calculateWeeklyProfit($startDate, $endDate)
    {
        return Sale::whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('total_amount - cost_price'));
    }

    protected function getSalesChartData($startDate, $endDate)
    {
        $sales = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Format for chart (fill in missing dates with 0)
        $chartData = [];
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $sale = $sales->firstWhere('date', $dateString);

            $chartData[] = [
                'date' => $currentDate->format('D M j'),
                'total' => $sale ? $sale->total : 0,
            ];

            $currentDate->addDay();
        }

        return $chartData;
    }

    protected function getRecentActivities()
    {
        $activities = collect();

        // Recent Sales
        $recentSales = Sale::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get()
            ->map(function ($sale) {
                return [
                    'type' => 'sale',
                    'icon' => 'check',
                    'iconColor' => 'green',
                    'title' => 'New sale completed',
                    'description' => 'Order #' . $sale->invoice_no . ' for $' . number_format($sale->total_amount, 2),
                    'time' => $sale->created_at->diffForHumans(),
                    'timestamp' => $sale->created_at
                ];
            });

        $activities = $activities->merge($recentSales);

        // New Products
        $newProducts = Product::orderBy('created_at', 'desc')
            ->take(1)
            ->get()
            ->map(function ($product) {
                return [
                    'type' => 'product',
                    'icon' => 'plus',
                    'iconColor' => 'blue',
                    'title' => 'New product added',
                    'description' => '"' . $product->name . '"',
                    'time' => $product->created_at->diffForHumans(),
                    'timestamp' => $product->created_at
                ];
            });

        $activities = $activities->merge($newProducts);

        // Returns
        $returns = ProductReturn::with('sale')
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->get()
            ->map(function ($return) {
                return [
                    'type' => 'return',
                    'icon' => 'x',
                    'iconColor' => 'red',
                    'title' => 'Return request received',
                    'description' => 'Order #' . ($return->sale->invoice_no ?? 'N/A'),
                    'time' => $return->created_at->diffForHumans(),
                    'timestamp' => $return->created_at
                ];
            });

        $activities = $activities->merge($returns);

        // Inventory Updates
        $inventoryUpdates = InventoryLog::with('product')
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->get()
            ->map(function ($log) {
                return [
                    'type' => 'inventory',
                    'icon' => 'pencil',
                    'iconColor' => 'purple',
                    'title' => 'Inventory updated',
                    'description' => ($log->product->name ?? 'Product') . ' stock adjusted',
                    'time' => $log->created_at->diffForHumans(),
                    'timestamp' => $log->created_at
                ];
            });

        $activities = $activities->merge($inventoryUpdates);

        // Sort all activities by timestamp
        return $activities->sortByDesc('timestamp')->values()->all();
    }




    public function getSalesData(Request $request)
    {
        $period = $request->input('period', 'week');
        
        switch ($period) {
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $groupBy = 'DATE(created_at)';
                $dateFormat = 'D M j';
                break;
                
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $groupBy = 'MONTH(created_at)';
                $dateFormat = 'M Y';
                break;
                
            case 'week':
            default:
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $groupBy = 'DATE(created_at)';
                $dateFormat = 'D M j';
                break;
        }

        $salesData = Sale::select(
            DB::raw($groupBy . ' as date_group'),
            DB::raw('SUM(total_amount) as total')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date_group')
        ->orderBy('date_group')
        ->get();

        // Format for chart
        $results = [];
        $currentDate = clone $startDate;
        
        while ($currentDate <= $endDate) {
            $dateKey = $period === 'year' 
                ? $currentDate->format('Y-m') 
                : $currentDate->format('Y-m-d');
                
            $sale = $salesData->first(function ($item) use ($dateKey, $period) {
                $itemDate = $period === 'year' 
                    ? Carbon::parse($item->date_group)->format('Y-m')
                    : Carbon::parse($item->date_group)->format('Y-m-d');
                return $itemDate === $dateKey;
            });

            $results[] = [
                'date' => $period === 'year' 
                    ? $currentDate->format($dateFormat)
                    : $currentDate->format($dateFormat),
                'total' => $sale ? $sale->total : 0
            ];

            $period === 'year' ? $currentDate->addMonth() : $currentDate->addDay();
        }

        return response()->json([
            'labels' => collect($results)->pluck('date'),
            'values' => collect($results)->pluck('total')
        ]);
    }
}
