<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Riwayat Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: #9B1A1A;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .app {
            width: 100%;
            max-width: 420px;
            height: 100vh;
            height: 100dvh;
            background: #F9F4EB;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        .header {
            background: #9B1A1A;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            padding: 52px 20px 22px;
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.22);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .back-btn svg {
            width: 20px;
            height: 20px;
            stroke: white;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .header h1 {
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .scroll-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px 16px 32px;
        }

        .scroll-area::-webkit-scrollbar {
            display: none;
        }

        /* ORDER CARD */
        .order-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            transition: transform 0.1s;
        }

        .order-card:active {
            transform: scale(0.98);
        }

        .oc-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .oc-id {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1A1A1A;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .badge-new {
            background: rgba(255, 178, 30, 0.15);
            color: #9A6200;
        }

        .badge-pending {
            background: rgba(30, 118, 210, 0.12);
            color: #1E76D2;
        }

        .badge-processing {
            background: rgba(158, 9, 15, 0.1);
            color: #9B1A1A;
        }

        .badge-delivering {
            background: rgba(30, 118, 210, 0.1);
            color: #1E76D2;
        }

        .badge-done {
            background: rgba(39, 174, 96, 0.12);
            color: #1E9E52;
        }

        .badge-rejected {
            background: rgba(150, 150, 150, 0.12);
            color: #666;
        }

        .oc-items {
            font-size: 0.82rem;
            color: #888;
            margin-bottom: 6px;
            line-height: 1.4;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .oc-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .oc-total {
            font-size: 0.9rem;
            font-weight: 700;
            color: #9B1A1A;
        }

        .oc-date {
            font-size: 0.76rem;
            color: #BBB;
        }

        /* EMPTY */
        .empty-state {
            padding: 80px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            color: #CCC;
            text-align: center;
        }

        .empty-state svg {
            width: 60px;
            height: 60px;
            stroke: #CCC;
            fill: none;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .empty-state p {
            font-size: 0.9rem;
        }

        .empty-state .btn-order {
            margin-top: 8px;
            background: #9B1A1A;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 28px;
            font-size: 0.9rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
        }

        @media (min-width: 480px) {
            body {
                background: radial-gradient(circle, #b81419 0%, #680507 100%);
            }

            .app {
                height: 850px;
                border-radius: 40px;
                border: 8px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            }
        }
    </style>
</head>

<body>
    <div class="app">
        <div class="header">
            <button class="back-btn" onclick="window.location.href='{{ route('home') }}?view=profile'">

                <svg viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
            </button>
            <h1>Riwayat Pesanan</h1>
        </div>

        <div class="scroll-area" id="order-list">
            @forelse($orders as $order)
                @php
                    $statusConfig = [
                        'NEW' => ['cls' => 'badge-new', 'label' => 'Pesanan Baru'],
                        'PENDING_VERIFICATION' => ['cls' => 'badge-pending', 'label' => 'Verifikasi QRIS'],
                        'PROCESSING' => ['cls' => 'badge-processing', 'label' => 'Dimasak'],
                        'DELIVERING' => ['cls' => 'badge-delivering', 'label' => 'Dikirim'],
                        'DONE' => ['cls' => 'badge-done', 'label' => 'Selesai'],
                        'REJECTED' => ['cls' => 'badge-rejected', 'label' => 'Ditolak'],
                    ][$order->status] ?? ['cls' => 'badge-new', 'label' => $order->status];
                @endphp
                <div class="order-card" onclick="window.location.href='{{ route('order.status', $order->id) }}'">
                    <div class="oc-top">
                        <span class="oc-id">#{{ $order->order_number }}</span>
                        <span class="status-badge {{ $statusConfig['cls'] }}">{{ $statusConfig['label'] }}</span>
                    </div>
                    <div class="oc-items">
                        {{ $order->orderItems->map(fn($i) => ($i->product_name_snapshot ?? 'Produk') . ' ×' . $i->quantity)->join(', ') }}
                    </div>
                    <div class="oc-bottom">
                        <span class="oc-total">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        <span class="oc-date">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <p>Belum ada riwayat pesanan</p>
                    <button class="btn-order" onclick="window.location.href='{{ route('home') }}'">Mulai Pesan</button>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // JS helpers if needed, but rendering is now server-side
    </script>
</body>

</html>