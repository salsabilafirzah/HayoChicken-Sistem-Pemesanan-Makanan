<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Alamat Tersimpan</title>
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
            padding: 16px 16px 100px;
        }

        .scroll-area::-webkit-scrollbar {
            display: none;
        }

        .addr-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            gap: 12px;
            align-items: flex-start;
            cursor: pointer;
            transition: box-shadow 0.2s;
        }

        .addr-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .addr-icon {
            width: 42px;
            height: 42px;
            background: #F5EFE6;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .addr-icon svg {
            width: 20px;
            height: 20px;
            stroke: #9B1A1A;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .addr-body {
            flex: 1;
        }

        .addr-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #9B1A1A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .addr-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1A1A1A;
            margin-bottom: 2px;
        }

        .addr-text {
            font-size: 0.82rem;
            color: #888;
            line-height: 1.5;
        }

        .addr-badge {
            background: #9B1A1A;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 50px;
        }

        .addr-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .addr-btn {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            border: none;
            transition: all 0.2s;
        }

        .addr-btn.edit {
            background: #F5EFE6;
            color: #9B1A1A;
        }

        .addr-btn.del {
            background: #FFF0F0;
            color: #D33;
        }

        .bottom-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px 20px 24px;
            background: linear-gradient(to top, #F9F4EB 80%, transparent);
        }

        .btn-add {
            width: 100%;
            background: #9B1A1A;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 16px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-add svg {
            width: 20px;
            height: 20px;
            stroke: white;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
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
            <h1>Alamat Tersimpan</h1>
        </div>
        <div class="scroll-area">
            <div class="addr-card">
                <div class="addr-icon"><svg viewBox="0 0 24 24">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg></div>
                <div class="addr-body">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div class="addr-label">Rumah</div>
                        <span class="addr-badge">Utama</span>
                    </div>
                    <div class="addr-name">Zainab Feizia</div>
                    <div class="addr-text">Jl. Kampus No. 12, Purwokerto, Banyumas, Jawa Tengah 53122</div>
                    <div class="addr-actions">
                        <button class="addr-btn edit">Edit</button>
                        <button class="addr-btn del">Hapus</button>
                    </div>
                </div>
            </div>
            <div class="addr-card">
                <div class="addr-icon"><svg viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                    </svg></div>
                <div class="addr-body">
                    <div class="addr-label">Kantor</div>
                    <div class="addr-name">UNSOED</div>
                    <div class="addr-text">Jl. Prof. Dr. HR. Boenyamin No. 708, Grendeng, Purwokerto Utara 53122</div>
                    <div class="addr-actions">
                        <button class="addr-btn edit">Edit</button>
                        <button class="addr-btn del">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-bar">
            <button class="btn-add" onclick="window.location.href='{{ route('address.add') }}'">
                <svg viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah Alamat Baru
            </button>
        </div>
    </div>
</body>

</html>