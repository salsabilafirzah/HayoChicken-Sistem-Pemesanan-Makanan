<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Pesanan Aktif</title>
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
            flex-shrink: 0;
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
            padding: 16px 16px 24px;
        }

        .scroll-area::-webkit-scrollbar {
            display: none;
        }

        .order-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .order-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .order-id {
            font-size: 0.78rem;
            color: #AAA;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-badge.proses {
            background: #FFF3CD;
            color: #B8860B;
        }

        .status-badge.diantar {
            background: #D4EDDA;
            color: #155724;
        }

        .status-badge.tiba {
            background: #CCE5FF;
            color: #004085;
        }

        .order-items {
            margin-bottom: 12px;
        }

        .order-item-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.88rem;
            color: #333;
            padding: 4px 0;
        }

        .order-item-name {
            color: #333;
        }

        .order-item-price {
            color: #9B1A1A;
            font-weight: 600;
        }

        .order-divider {
            border: none;
            border-top: 1.5px dashed #E8DDD0;
            margin: 10px 0;
        }

        .order-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-total-lbl {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1A1A1A;
        }

        .order-total-val {
            font-size: 0.95rem;
            font-weight: 700;
            color: #9B1A1A;
        }

        /* Progress tracker */
        .progress-wrap {
            margin-top: 14px;
        }

        .progress-label {
            font-size: 0.78rem;
            color: #999;
            margin-bottom: 8px;
        }

        .progress-track {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .p-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }

        .p-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            z-index: 1;
        }

        .p-dot.done {
            background: #9B1A1A;
            color: white;
        }

        .p-dot.active {
            background: #9B1A1A;
            color: white;
            box-shadow: 0 0 0 4px rgba(155, 26, 26, 0.2);
        }

        .p-dot.pending {
            background: #E8DDD0;
            color: #AAA;
        }

        .p-txt {
            font-size: 0.65rem;
            color: #888;
            margin-top: 5px;
            text-align: center;
        }

        .p-line {
            flex: 1;
            height: 3px;
            background: #E8DDD0;
            margin-top: -11px;
        }

        .p-line.done {
            background: #9B1A1A;
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
            <h1>Pesanan Aktif</h1>
        </div>
        <div class="scroll-area">
            <div class="order-card">
                <div class="order-top">
                    <div class="order-id">#HC-2024-0042 · Hari ini, 11:30</div>
                    <div class="status-badge diantar">Sedang Diantar</div>
                </div>
                <div class="order-items">
                    <div class="order-item-row">
                        <span class="order-item-name">1x Ayam Goreng Crispy</span>
                        <span class="order-item-price">Rp12.000</span>
                    </div>
                    <div class="order-item-row">
                        <span class="order-item-name">1x Es Teh Lemon</span>
                        <span class="order-item-price">Rp5.000</span>
                    </div>
                </div>
                <hr class="order-divider">
                <div class="order-total-row">
                    <span class="order-total-lbl">Total</span>
                    <span class="order-total-val">Rp17.000</span>
                </div>
                <div class="progress-wrap">
                    <div class="progress-label">Status Pengiriman</div>
                    <div class="progress-track">
                        <div class="p-step">
                            <div class="p-dot done">✓</div>
                            <div class="p-txt">Dipesan</div>
                        </div>
                        <div class="p-line done"></div>
                        <div class="p-step">
                            <div class="p-dot done">✓</div>
                            <div class="p-txt">Diproses</div>
                        </div>
                        <div class="p-line done"></div>
                        <div class="p-step">
                            <div class="p-dot active"><svg viewBox="0 0 24 24" width="12" height="12"
                                    stroke="currentColor" fill="none" stroke-width="2">
                                    <circle cx="7" cy="17" r="2" />
                                    <circle cx="17" cy="17" r="2" />
                                    <path d="M5 17H3v-6l2-5h9l4 5h3v6h-2" />
                                    <path d="M15 6h-6" />
                                </svg></div>
                            <div class="p-txt">Diantar</div>
                        </div>
                        <div class="p-line"></div>
                        <div class="p-step">
                            <div class="p-dot pending"><svg viewBox="0 0 24 24" width="12" height="12"
                                    stroke="currentColor" fill="none" stroke-width="2">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                    <line x1="12" y1="22.08" x2="12" y2="12" />
                                </svg></div>
                            <div class="p-txt">Tiba</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>