<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function summary()
    {
        $last30Days = now()->subDays(30);
        
        // [SEDANG #2] Filter status DONE
        $totalRevenue = Order::where('status', 'DONE')
                             ->where('created_at', '>=', $last30Days)
                             ->sum('total_amount');
                             
        $orderCount = Order::where('status', 'DONE')
                           ->where('created_at', '>=', $last30Days)
                           ->count();
        
        $todayRevenue = Order::where('status', 'DONE')
                             ->whereDate('created_at', today())
                             ->sum('total_amount');
                             
        $newOrderCount = Order::whereIn('status', ['NEW', 'PENDING_VERIFICATION'])->count();

        // Top Products [SEDANG #2]
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'DONE')
            ->where('orders.created_at', '>=', $last30Days)
            ->select('product_name_snapshot as name', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('product_name_snapshot')
            ->orderBy('total_qty', 'DESC')
            ->limit(5)
            ->get();

        // [SEDANG #4] BOM Forecasting Logic
        $sales30Days = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $last30Days)
            ->where('orders.status', 'DONE')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('order_items.product_id')
            ->get();

        $materialNeeds = [];
        foreach ($sales30Days as $sale) {
            $bomItems = \App\Models\MenuBom::where('product_id', $sale->product_id)->get();
            foreach ($bomItems as $bom) {
                $materialId = $bom->raw_material_id;
                $materialNeeds[$materialId] = ($materialNeeds[$materialId] ?? 0) + ($bom->quantity_needed * $sale->total_qty);
            }
        }

        $forecasting = [];
        $rawMaterials = \App\Models\RawMaterial::all();
        foreach ($rawMaterials as $rm) {
            $needs = $materialNeeds[$rm->id] ?? 0;
            if ($needs == 0) continue;

            // [SEDANG #3] Logika Label Forecasting Terbalik (Diperbaiki)
            if ($rm->current_stock < $rm->minimum_threshold) {
                $status = "Restock Segera";
            } elseif ($rm->current_stock < ($needs * 1.2)) {
                $status = "Prioritas Tinggi";
            } else {
                $status = "Stok Aman";
            }

            $forecasting[] = [
                'material' => $rm->name,
                'estimate' => $needs,
                'unit' => $rm->unit,
                'current' => $rm->current_stock,
                'status' => $status
            ];
        }

        return response()->json([
            'success' => true,
            'last_30_days' => [
                'revenue' => (int) $totalRevenue,
                'orders' => $orderCount,
                'avg_order_value' => $orderCount > 0 ? (int) ($totalRevenue / $orderCount) : 0,
                'top_products' => $topProducts,
            ],
            'today' => [
                'revenue' => (int) $todayRevenue,
                'new_orders' => $newOrderCount,
            ],
            'forecasting' => $forecasting
        ]);
    }
}
