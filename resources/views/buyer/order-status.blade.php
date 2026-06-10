<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Status Pesanan</title>
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
            padding: 20px 16px 120px;
        }

        .scroll-area::-webkit-scrollbar {
            display: none;
        }

        /* ORDER INFO */
        .order-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .oc-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
        }

        .oc-label {
            font-size: 0.83rem;
            color: #888;
        }

        .oc-val {
            font-size: 0.83rem;
            font-weight: 600;
            color: #333;
        }

        .oc-val.red {
            color: #9B1A1A;
        }

        .oc-val.orange {
            color: #C8930D;
        }

        /* STATUS BADGE */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-top: 4px;
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

        /* REJECTED NOTICE */
        .rejected-notice {
            background: #FFF0F0;
            border: 1.5px solid rgba(155, 26, 26, 0.25);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 0.83rem;
            color: #9B1A1A;
            line-height: 1.5;
            display: none;
        }

        .rejected-notice.show {
            display: block;
        }

        .rejected-notice strong {
            display: block;
            margin-bottom: 4px;
        }

        /* QRIS PENDING NOTICE */
        .qris-notice {
            background: #FFF8EC;
            border: 1.5px solid #FFD080;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 0.83rem;
            color: #7A5000;
            line-height: 1.5;
            display: none;
        }

        .qris-notice.show {
            display: block;
        }

        /* TIMELINE */
        .timeline-card {
            background: white;
            border-radius: 16px;
            padding: 20px 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .tl-card-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 16px;
        }

        .timeline {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .tl-item {
            display: flex;
            gap: 14px;
            position: relative;
        }

        .tl-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 40px;
            flex-shrink: 0;
        }

        .tl-dot {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            z-index: 2;
        }

        .tl-dot svg {
            width: 20px;
            height: 20px;
            stroke: white;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .tl-dot.done {
            background: #2ECC71;
        }

        .tl-dot.active {
            background: #9B1A1A;
        }

        .tl-dot.active-blue {
            background: #1E76D2;
        }

        .tl-dot.rejected-dot {
            background: #999;
        }

        .tl-dot.next {
            background: #F5A623;
        }

        .tl-dot.waiting {
            background: #DDD;
        }

        .tl-dot.waiting svg {
            stroke: #999;
        }

        .tl-line {
            width: 3px;
            flex: 1;
            min-height: 20px;
            background: #E8DDD0;
            margin: 2px 0;
        }

        .tl-line.done-line {
            background: #2ECC71;
        }

        .tl-right {
            flex: 1;
            padding-bottom: 22px;
        }

        .tl-right:last-child {
            padding-bottom: 0;
        }

        .tl-title {
            font-size: 0.93rem;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .tl-title.done {
            color: #1A1A1A;
        }

        .tl-title.active {
            color: #9B1A1A;
        }

        .tl-title.active-blue {
            color: #1E76D2;
        }

        .tl-title.rejected {
            color: #999;
        }

        .tl-title.next {
            color: #F5A623;
        }

        .tl-title.waiting {
            color: #CCC;
        }

        .tl-sub {
            font-size: 0.78rem;
            color: #AAA;
        }

        /* SUMMARY */
        .sum-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .sum-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
        }

        .sum-label {
            font-size: 0.85rem;
            color: #666;
        }

        .sum-val {
            font-size: 0.85rem;
            color: #333;
        }

        .sum-divider {
            border: none;
            border-top: 1.5px dashed #E8DDD0;
            margin: 8px 0;
        }

        .sum-total-label {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1A1A1A;
        }

        .sum-total-val {
            font-size: 0.95rem;
            font-weight: 700;
            color: #9B1A1A;
        }

        /* BOTTOM */
        .bottom-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px 20px;
            background: linear-gradient(to top, #F9F4EB 80%, transparent);
            z-index: 100;
        }

        .btn-home {
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
            transition: background 0.2s, transform 0.1s;
        }

        .btn-home:hover {
            background: #7f1414;
        }

        .btn-home:active {
            transform: scale(0.98);
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
            <button class="back-btn" onclick="window.location.href='{{ route('home') }}'">
                <svg viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
            </button>
            <h1>Status Pesanan</h1>
        </div>

        <div class="scroll-area">
            <div class="order-card">
                <div class="oc-row">
                    <span class="oc-label">ID Pesanan</span>
                    <span class="oc-val" id="sp-id">#HC-0001</span>
                </div>
                <div class="oc-row">
                    <span class="oc-label">Metode Bayar</span>
                    <span class="oc-val" id="sp-pay">COD</span>
                </div>
                <div class="oc-row">
                    <span class="oc-label">Total</span>
                    <span class="oc-val red" id="sp-total">Rp0</span>
                </div>
                <div class="oc-row">
                    <span class="oc-label">Status</span>
                    <span id="sp-badge"></span>
                </div>
            </div>

            <!-- Notifikasi QRIS pending -->
            <div class="qris-notice" id="qris-notice">
                <svg style="width:16px;height:16px;margin-right:4px;vertical-align:middle" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="#7A5000" fill="none" stroke-width="2" />
                    <polyline points="12 6 12 12 16 14" stroke="#7A5000" fill="none" stroke-width="2" />
                </svg> Pesanan menunggu verifikasi pembayaran QRIS oleh penjual. Proses ini biasanya memerlukan beberapa
                menit.
            </div>

            <!-- Notifikasi Ditolak -->
            <div class="rejected-notice" id="rejected-notice">
                <strong><svg style="width:16px;height:16px;margin-right:4px;vertical-align:middle" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18" stroke="#9B1A1A" fill="none" stroke-width="2.5"
                            stroke-linecap="round" />
                        <line x1="6" y1="6" x2="18" y2="18" stroke="#9B1A1A" fill="none" stroke-width="2.5"
                            stroke-linecap="round" />
                    </svg> Pesanan Ditolak</strong>
                <span id="rejected-reason">Pesanan tidak dapat diproses.</span>
            </div>

            <div class="timeline-card">
                <div class="tl-card-title">Riwayat Status</div>
                <div class="timeline" id="timeline"></div>
            </div>

            <div class="sum-card">
                <div class="sum-row">
                    <span class="sum-label" id="sp-subtotal-label">Subtotal (0 item)</span>
                    <span class="sum-val" id="sp-subtotal-val">Rp0</span>
                </div>
                <hr class="sum-divider">
                <div class="sum-row">
                    <span class="sum-total-label">Total</span>
                    <span class="sum-total-val" id="sp-total-sum">Rp0</span>
                </div>
            </div>
        </div>

        <div class="bottom-bar">
            <button class="btn-home" onclick="window.location.href='{{ route('home') }}'">Kembali ke Beranda</button>
        </div>
    </div>

    <script>
        function rp(n) { return 'Rp' + n.toLocaleString('id-ID'); }

        const order = @json($order);
        const currentStatus = order.status.toLowerCase();
        const payMethod = order.payment_method.toLowerCase();

        const isQris = payMethod.includes('qris');
        const isCash = payMethod === 'cash';
        const isRejected = currentStatus === 'rejected';

        // Definisi steps berdasarkan metode pembayaran
        let steps;
        if (isQris) {
            steps = [
                { key: 'new', title: 'Pesanan Diterima', sub: 'Pesanan berhasil dibuat' },
                { key: 'pending_verification', title: 'Verifikasi Pembayaran', sub: 'Penjual sedang memverifikasi QRIS' },
                { key: 'processing', title: 'Sedang Dimasak', sub: 'Pesanan sedang disiapkan' },
                { key: 'delivering', title: 'Dalam Pengiriman', sub: 'Estimasi 15–20 menit' },
                { key: 'done', title: 'Pesanan Tiba', sub: 'Selamat menikmati pesananmu!' }
            ];
        } else if (isCash) {
            steps = [
                { key: 'new', title: 'Pesanan Diterima', sub: 'Pesanan berhasil dibuat' },
                { key: 'processing', title: 'Sedang Dimasak', sub: 'Pesanan sedang disiapkan' },
                { key: 'delivering', title: 'Siap Diambil', sub: 'Silakan ambil pesanan di kasir' },
                { key: 'done', title: 'Selesai', sub: 'Pesanan sudah diambil' }
            ];
        } else {
            steps = [
                { key: 'new', title: 'Pesanan Diterima', sub: 'Pesanan berhasil dibuat' },
                { key: 'processing', title: 'Sedang Dimasak', sub: 'Pesanan sedang disiapkan' },
                { key: 'delivering', title: 'Dalam Pengiriman', sub: 'Estimasi 15–20 menit' },
                { key: 'done', title: 'Pesanan Tiba', sub: 'Selamat menikmati pesananmu!' }
            ];
        }

        const statusOrder = steps.map(s => s.key);
        const currentIdx = statusOrder.indexOf(currentStatus);

        const checkSvg = `<svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>`;
        const clockSvg = `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`;
        const xSvg = `<svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;

        const tl = document.getElementById('timeline');

        if (isRejected) {
            steps.forEach((step, i) => {
                const isLast = i === steps.length - 1;
                tl.innerHTML += buildStep(step, i, 'done', 'done', checkSvg, isLast, true);
            });
            tl.innerHTML += `
    <div class="tl-item">
        <div class="tl-left">
            <div class="tl-dot rejected-dot">${xSvg}</div>
        </div>
        <div class="tl-right">
            <div class="tl-title rejected">Pesanan Ditolak</div>
            <div class="tl-sub">${order.status_logs[0]?.notes || 'Pesanan tidak dapat diproses'}</div>
        </div>
    </div>`;
            document.getElementById('rejected-notice').classList.add('show');
            if (order.status_logs.length > 0) {
                document.getElementById('rejected-reason').textContent = order.status_logs[order.status_logs.length - 1].notes;
            }
        } else {
            steps.forEach((step, i) => {
                const isLast = i === steps.length - 1;
                let dotCls, titleCls, icon;
                if (i < currentIdx) {
                    dotCls = 'done'; titleCls = 'done'; icon = checkSvg;
                } else if (i === currentIdx) {
                    dotCls = (step.key === 'pending_verification') ? 'active-blue' : 'active';
                    titleCls = dotCls;
                    icon = (step.key === 'pending_verification') ? clockSvg : checkSvg;
                } else if (i === currentIdx + 1 && currentStatus !== 'pending_verification') {
                    // Hanya tandai "next" (orange) jika status sekarang BUKAN menunggu verifikasi
                    dotCls = 'next'; titleCls = 'next'; icon = clockSvg;
                } else {
                    dotCls = 'waiting'; titleCls = 'waiting'; icon = clockSvg;
                }
                tl.innerHTML += buildStep(step, i, dotCls, titleCls, icon, isLast, i < currentIdx);
            });
        }

        function buildStep(step, i, dotCls, titleCls, icon, isLast, lineDone) {
            return `
    <div class="tl-item">
        <div class="tl-left">
            <div class="tl-dot ${dotCls}">${icon}</div>
            ${!isLast ? `<div class="tl-line ${lineDone ? 'done-line' : ''}"></div>` : ''}
        </div>
        <div class="tl-right">
            <div class="tl-title ${titleCls}">${step.title}</div>
            <div class="tl-sub">${step.sub}</div>
        </div>
    </div>`;
        }

        if (currentStatus === 'pending_verification') document.getElementById('qris-notice').classList.add('show');

        const badgeMap = {
            new: { cls: 'badge-new', label: 'Pesanan Baru' },
            pending_verification: { cls: 'badge-pending', label: 'Menunggu Verifikasi' },
            processing: { cls: 'badge-processing', label: 'Sedang Dimasak' },
            delivering: { cls: 'badge-delivering', label: isCash ? 'Siap Diambil' : 'Dalam Pengiriman' },
            done: { cls: 'badge-done', label: 'Selesai' },
            rejected: { cls: 'badge-rejected', label: 'Ditolak' }
        };
        const b = badgeMap[currentStatus] || badgeMap.new;
        document.getElementById('sp-badge').innerHTML = `<span class="status-badge ${b.cls}">${b.label}</span>`;

        document.getElementById('sp-pay').textContent = order.payment_method;
        document.getElementById('sp-id').textContent = '#' + order.order_number;
        document.getElementById('sp-total').textContent = rp(order.total_amount);
        document.getElementById('sp-subtotal-label').textContent = `Subtotal (${order.order_items.length} item)`;
        document.getElementById('sp-subtotal-val').textContent = rp(order.total_amount);
        document.getElementById('sp-total-sum').textContent = rp(order.total_amount);
    </script>
    </script>
</body>

</html>