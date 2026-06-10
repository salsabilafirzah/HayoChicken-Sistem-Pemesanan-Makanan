<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\MenuBom;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    public function dashboard()
    {
        // 1. Core Analytics (Last 30 Days)
        $last30Days = now()->subDays(30);
        $totalRevenue = Order::where('status', 'DONE')
                             ->where('created_at', '>=', $last30Days)
                             ->sum('total_amount');
        $orderCount = Order::where('created_at', '>=', $last30Days)->count();
        $avgValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        // 2. Top Products
        $topProducts = DB::table('order_items')
            ->select('product_name_snapshot as name', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_name_snapshot')
            ->orderBy('total_qty', 'DESC')
            ->limit(5)
            ->get();

        // 3. Recent Transactions (with items)
        $recentOrders = Order::with(['user', 'orderItems'])->orderBy('created_at', 'DESC')->limit(20)->get();

        $todayOrderCount = Order::whereDate('created_at', today())->count();
        $newOrderCount = Order::whereIn('status', ['NEW', 'PENDING_VERIFICATION'])->count();

        // 4. Smart Forecasting (BOM Logic - Aggregated for ALL products sold in 30 days)
        $sales30Days = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $last30Days)
            ->where('orders.status', '!=', 'REJECTED')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('order_items.product_id')
            ->get();

        $materialNeeds = [];
        foreach ($sales30Days as $sale) {
            $bomItems = MenuBom::where('product_id', $sale->product_id)->get();
            foreach ($bomItems as $bom) {
                $materialId = $bom->raw_material_id;
                if (!isset($materialNeeds[$materialId])) {
                    $materialNeeds[$materialId] = 0;
                }
                $materialNeeds[$materialId] += ($bom->quantity_needed * $sale->total_qty);
            }
        }

        $forecasting = [];
        $rawMaterials = RawMaterial::all();
        foreach ($rawMaterials as $rm) {
            $needs = $materialNeeds[$rm->id] ?? 0;
            if ($needs == 0) continue; // Skip materials not needed

            $status = "Stok Aman";
            if ($rm->current_stock < ($needs + $rm->minimum_threshold)) {
                $status = "Prioritas Tinggi";
            } elseif ($rm->current_stock < ($needs * 1.5)) {
                $status = "Restock Segera";
            }

            $forecasting[] = [
                'material' => $rm->name,
                'estimate' => $needs,
                'unit' => $rm->unit,
                'current' => $rm->current_stock,
                'status' => $status
            ];
        }

        $allProducts = Product::orderBy('name')->get();

        // 5. Daily Sales for Chart (Last 7 Days)
        $dailySales = Order::where('status', 'DONE')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return view('seller.dashboard', compact(
            'totalRevenue', 'orderCount', 'avgValue', 
            'topProducts', 'recentOrders', 'forecasting', 'allProducts',
            'todayOrderCount', 'newOrderCount', 'dailySales'
        ));
    }
}
