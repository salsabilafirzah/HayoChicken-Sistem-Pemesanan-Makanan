<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\OrderStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Handle the checkout process.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string',
            'delivery_note' => 'nullable|string|max:500',
            'payment_method' => 'required|in:CASH,COD,QRIS_MANUAL',
            'payment_receipt' => 'required_if:payment_method,QRIS_MANUAL|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        
        // Ambil item dari database yang di-check (Security: Prevents price manipulation)
        $cartItems = \App\Models\CartItem::with('product')
            ->where('user_id', $user->id)
            ->where('is_checked', true)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Keranjang kosong atau tidak ada item yang dipilih.'], 422);
        }

        // [Bug #4] Validasi produk sebelum transaksi
        foreach ($cartItems as $cartItem) {
            if (!$cartItem->product || !$cartItem->product->is_available) {
                return response()->json([
                    'success' => false,
                    'message' => "Produk '" . ($cartItem->product->name ?? 'Unknown') . "' tidak tersedia. Silakan hapus dari keranjang."
                ], 422);
            }
        }

        return DB::transaction(function () use ($request, $user, $cartItems) {
            $totalAmount = 0;
            $itemsToProcess = [];

            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $qty = $cartItem->quantity;
                
                // [SEDANG #1] Hitung extras
                $extrasTotal = 0;
                $extrasSnapshot = $cartItem->selected_extras_snapshot ?? [];
                if (!empty($extrasSnapshot)) {
                    foreach ($extrasSnapshot as $extraName) {
                        // Cari berdasarkan nama di lingkup produk ini
                        $extra = \App\Models\ProductExtra::where('product_id', $product->id)
                            ->where('name', $extraName)
                            ->first();
                        if ($extra) $extrasTotal += $extra->additional_price;
                    }
                }
                
                $unitPrice = $product->base_price + $extrasTotal;
                $subtotal = $unitPrice * $qty;
                $totalAmount += $subtotal;

                $itemsToProcess[] = [
                    'product_id' => $product->id,
                    'name_snapshot' => $product->name,
                    'price_at_order' => $unitPrice,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                    'extras_snapshot' => $extrasSnapshot, 
                    'note' => $cartItem->note, // [Fase 14] Ambil note per item
                ];
            }

            // Handle QRIS receipt upload
            $receiptPath = null;
            if ($request->hasFile('payment_receipt')) {
                $receiptPath = $request->file('payment_receipt')->store('receipts', 'public');
            }

            // Generate Order Number
            $date = now()->format('Ymd');
            $todayCount = Order::whereDate('created_at', today())->count();
            $sequence = str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);
            $orderNumber = "HC-{$date}-{$sequence}";

            // Create Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'status' => ($request->payment_method === 'QRIS_MANUAL') ? 'PENDING_VERIFICATION' : 'NEW',
                'delivery_address' => $request->delivery_address,
                'delivery_note' => $request->delivery_note,
                'payment_method' => $request->payment_method,
                'payment_receipt' => $receiptPath,
                'total_amount' => $totalAmount,
            ]);

            // Create Order Items
            foreach ($itemsToProcess as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name_snapshot' => $item['name_snapshot'],
                    'quantity' => $item['quantity'],
                    'price_at_order' => $item['price_at_order'],
                    'selected_extras_snapshot' => $item['extras_snapshot'],
                    'subtotal' => $item['subtotal'],
                    'note' => $item['note'], // [Fase 14] Simpan note per item
                ]);
            }

            // Create Initial Status Log
            OrderStatusLog::create([
                'order_id' => $order->id,
                'from_status' => null,
                'to_status' => $order->status,
                'notes' => 'Pesanan berhasil dibuat oleh pelanggan melalui ' . $request->payment_method . '.',
                'changed_by_user_id' => $user->id,
            ]);

            // [KRITIS #5] Hapus item yang di-checkout dari keranjang
            \App\Models\CartItem::where('user_id', $user->id)
                ->where('is_checked', true)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikirim!',
                'data' => $order,
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);
        });
    }

    /**
     * Update order status (State Machine logic).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // [MINOR #4] Rejection reason wajib saat REJECTED
        if ($newStatus === 'REJECTED' && empty($request->note)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Alasan penolakan wajib diisi.'], 422);
            }
            return back()->with('error', 'Alasan penolakan wajib diisi.');
        }

        // Simple State Machine Logic
        $allowedTransitions = [
            'NEW' => ['PROCESSING', 'REJECTED'],
            'PENDING_VERIFICATION' => ['PROCESSING', 'REJECTED'],
            'PROCESSING' => ['DELIVERING', 'REJECTED'],
            'DELIVERING' => ['DONE'],
        ];

        if (!isset($allowedTransitions[$oldStatus]) || !in_array($newStatus, $allowedTransitions[$oldStatus])) {
            $message = "Transisi status dari {$oldStatus} ke {$newStatus} tidak diperbolehkan.";
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        // Update status & rejection_reason [MINOR #4]
        $updateData = ['status' => $newStatus];
        if ($newStatus === 'REJECTED') {
            $updateData['rejection_reason'] = $request->note;
        }
        $order->update($updateData);

        // Audit Trail
        OrderStatusLog::create([
            'order_id' => $order->id,
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'notes' => $request->note ?? "Status diubah dari {$oldStatus} menjadi {$newStatus}",
            'changed_by_user_id' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status pesanan {$order->order_number} berhasil diperbarui.",
                'data' => $order
            ]);
        }

        return back()->with('success', "Status pesanan {$order->order_number} berhasil diperbarui.");
    }

    /**
     * List user's orders. [SEDANG #7]
     */
    public function index()
    {
        $orders = Order::with('orderItems')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->orderBy('created_at', 'DESC')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Show order status & items. [SEDANG #7]
     */
    public function show(Order $order)
    {
        if ($order->user_id !== \Illuminate\Support\Facades\Auth::id() && \Illuminate\Support\Facades\Auth::user()->role !== 'SELLER') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $order->load(['orderItems', 'statusLogs', 'user'])
        ]);
    }

    /**
     * List all orders (Seller Only). [Bug #7]
     */
    public function allOrders(\Illuminate\Http\Request $request)
    {
        $query = Order::with(['orderItems', 'user'])
            ->orderBy('created_at', 'DESC');
            
        if ($request->has('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->get();
            
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
