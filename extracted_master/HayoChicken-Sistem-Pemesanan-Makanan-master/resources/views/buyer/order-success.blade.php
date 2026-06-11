<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Pesanan Berhasil</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body {
            background: #9B1A1A;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh; min-height: 100dvh;
            display: flex; justify-content: center; align-items: center;
            overflow: hidden;
        }
        .app {
            width: 100%; max-width: 420px;
            height: 100vh; height: 100dvh;
            background: #F9F4EB;
            display: flex; flex-direction: column;
            overflow: hidden; position: relative;
        }

        .content {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 32px 24px; text-align: center;
            overflow-y: auto;
        }
        .content::-webkit-scrollbar { display: none; }

        /* CHECK ICON */
        .check-circle {
            width: 100px; height: 100px; background: #9B1A1A;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(155,26,26,0.3);
        }
        .check-circle svg {
            width: 52px; height: 52px; stroke: white; fill: none;
            stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;
        }

        h1 { font-size: 1.6rem; font-weight: 800; color: #1A1A1A; margin-bottom: 10px; }
        .desc { font-size: 0.9rem; color: #888; line-height: 1.6; margin-bottom: 28px; max-width: 280px; }

        /* INFO CARD */
        .info-card {
            background: #EDE4D6; border-radius: 16px;
            padding: 16px 20px; width: 100%;
            margin-bottom: 28px; text-align: left;
        }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; }
        .info-label { font-size: 0.85rem; color: #888; }
        .info-val { font-size: 0.85rem; font-weight: 600; color: #333; }
        .info-val.accent { color: #9B1A1A; }

        /* BUTTONS */
        .btn-primary {
            width: 100%; background: #9B1A1A; color: white;
            border: none; border-radius: 50px;
            padding: 16px; font-size: 1rem; font-weight: 700;
            cursor: pointer; font-family: inherit;
            margin-bottom: 14px;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-primary:hover { background: #7f1414; }
        .btn-primary:active { transform: scale(0.98); }
        .btn-text {
            background: none; border: none; color: #9B1A1A;
            font-size: 0.95rem; font-weight: 700; cursor: pointer;
            font-family: inherit;
        }

        @media (min-width: 480px) {
            body { background: radial-gradient(circle, #b81419 0%, #680507 100%); }
            .app { height: 850px; border-radius: 40px; border: 8px solid rgba(255,255,255,0.1); box-shadow: 0 20px 50px rgba(0,0,0,0.4); }
        }
    </style>
</head>
<body>
<div class="app">
    <div class="content">
        <div class="check-circle">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1>Pesanan Berhasil!</h1>
        <p class="desc">Penjual Hayo Chicken sedang memproses pesananmu. Kami akan segera mengantarkan!</p>

        <div class="info-card">
            <div class="info-row">
                <span class="info-label">ID Pesanan</span>
                <span class="info-val" id="order-id">#{{ $order->order_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Estimasi</span>
                <span class="info-val accent" id="order-est">15-20 menit</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total</span>
                <span class="info-val" id="order-total">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <button class="btn-primary" onclick="window.location.href='{{ route('order.status', $order->id) }}'">Pantau Pesanan</button>
        <button class="btn-text" onclick="window.location.href='{{ route('home') }}'">Kembali ke Beranda</button>
    </div>
</div>

<script>
    // Data loaded via Blade
</script>
</body>
</html>
