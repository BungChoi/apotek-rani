<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display apoteker dashboard with summary data.
     */
    public function index()
    {
        // Get today's date
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $lastWeek = Carbon::now()->subDays(7);
        
        // Get sales statistics
        $todaySales = Sale::whereDate('sale_date', $today)->get();
        $todaySalesCount = $todaySales->count();
        $todaySalesAmount = $todaySales->sum('total_amount');
        
        // Get monthly sales data
        $monthlySales = Sale::whereMonth('sale_date', Carbon::now()->month)
            ->whereYear('sale_date', Carbon::now()->year)
            ->get();
        $monthlySalesCount = $monthlySales->count();
        $monthlySalesAmount = $monthlySales->sum('total_amount');
        $dailyAverage = $monthlySalesCount > 0 ? $monthlySalesAmount / Carbon::now()->daysInMonth : 0;
        
        // Get product statistics
        $totalProducts = Product::count();
        $availableProducts = Product::where('status', 'tersedia')->count();
        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', 10)
            ->where('status', 'tersedia')
            ->count();
        $expiredProducts = Product::where('status', 'kadaluarsa')->count();
        
        // Get recent transactions
        $recentTransactions = Sale::with(['customer'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get top selling products
        $topProducts = Product::select('products.*', DB::raw('SUM(sale_details.quantity) as total_quantity'))
            ->join('sale_details', 'products.id', '=', 'sale_details.product_id')
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->whereDate('sales.sale_date', '>=', $startOfMonth)
            ->groupBy('products.id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
            
        // Get sales data for chart (last 7 days)
        $salesChart = Sale::select(
                DB::raw('DATE(sale_date) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereDate('sale_date', '>=', $lastWeek)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return $item->total;
            });
            
        // Fill missing dates with zero
        $chartData = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $chartData[$date] = isset($salesChart[$date]) ? $salesChart[$date] : 0;
        }
        
        // Customer count
        $customerCount = User::where('role', 'pelanggan')->count();
        
        return view('apoteker.dashboard', compact(
            'todaySalesCount',
            'todaySalesAmount',
            'totalProducts',
            'availableProducts',
            'lowStockProducts',
            'expiredProducts',
            'recentTransactions',
            'topProducts',
            'monthlySalesAmount',
            'monthlySalesCount',
            'dailyAverage',
            'chartData',
            'customerCount'
        ));
    }
}
