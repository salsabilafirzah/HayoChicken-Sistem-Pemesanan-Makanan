<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Notifikasi</title>
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

        .notif-card {
            background: white;
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            gap: 12px;
            align-items: flex-start;
            cursor: pointer;
            transition: box-shadow 0.2s;
            position: relative;
        }

        .notif-card.unread {
            border-left: 3px solid #9B1A1A;
        }

        .notif-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.2rem;
        }

        .notif-icon.order {
            background: #FFF3CD;
        }

        .notif-icon.promo {
            background: #D4EDDA;
        }

        .notif-icon.info {
            background: #CCE5FF;
        }

        .notif-body {
            flex: 1;
        }

        .notif-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 3px;
        }

        .notif-text {
            font-size: 0.82rem;
            color: #888;
            line-height: 1.5;
        }

        .notif-time {
            font-size: 0.72rem;
            color: #BBAA99;
            margin-top: 5px;
        }

        .unread-dot {
            width: 8px;
            height: 8px;
            background: #9B1A1A;
            border-radius: 50%;
            position: absolute;
            top: 14px;
            right: 14px;
        }

        .section-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #AAA;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 8px 4px 8px;
            margin-top: 4px;
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
            <h1>Notifikasi</h1>
        </div>
        <div class="scroll-area">
            <div class="section-label">Terbaru</div>

            <div class="notif-card unread">
                <div class="notif-icon order">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="#B8860B" fill="none" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13" />
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                        <circle cx="5.5" cy="18.5" r="2.5" />
                        <circle cx="18.5" cy="18.5" r="2.5" />
                    </svg>
                </div>

                <div class="notif-body">
                    <div class="notif-title">Pesanan Sedang Diantar</div>
                    <div class="notif-text">Pesanan #HC-2024-0042 sedang dalam perjalanan ke alamat kamu.</div>
                    <div class="notif-time">Baru saja</div>
                </div>
                <div class="unread-dot"></div>
            </div>

            <div class="notif-card unread">
                <div class="notif-icon promo">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="#155724" fill="none" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                </div>

                <div class="notif-body">
                    <div class="notif-title">Promo Hari Ini!</div>
                    <div class="notif-text">Diskon 20% untuk semua menu paket. Berlaku sampai jam 21.00 hari ini!</div>
                    <div class="notif-time">1 jam yang lalu</div>
                </div>
                <div class="unread-dot"></div>
            </div>

            <div class="section-label">Sebelumnya</div>

            <div class="notif-card">
                <div class="notif-icon order" style="background:#D4EDDA;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="#155724" fill="none" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>

                <div class="notif-body">
                    <div class="notif-title">Pesanan Berhasil Diterima</div>
                    <div class="notif-text">Pesanan #HC-2024-0038 telah diterima. Terima kasih sudah memesan di Hayo
                        Chicken!</div>
                    <div class="notif-time">Kemarin, 13:20</div>
                </div>
            </div>

            <div class="notif-card">
                <div class="notif-icon info">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="#004085" fill="none" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="16" x2="12" y2="12" />
                        <line x1="12" y1="8" x2="12.01" y2="8" />
                    </svg>
                </div>

                <div class="notif-body">
                    <div class="notif-title">Menu Baru Tersedia</div>
                    <div class="notif-text">Coba menu terbaru kami: Ayam Geprek Mozzarella. Rasanya pasti bikin nagih!
                    </div>
                    <div class="notif-time">3 hari yang lalu</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>