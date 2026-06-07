<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Checkout</title>
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

        /* SECTION CARD */
        .section-card {
            background: white;
            border-radius: 16px;
            padding: 18px 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 14px;
        }

        .field-input {
            width: 100%;
            padding: 12px 14px;
            background: #F5EFE6;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.88rem;
            color: #333;
            outline: none;
            margin-bottom: 10px;
        }

        .field-input:last-child {
            margin-bottom: 0;
        }

        .field-input::placeholder {
            color: #BBAA99;
        }

        .field-input:focus {
            background: #EDE4D6;
        }

        .field-input.error {
            border: 1.5px solid #9B1A1A;
        }

        /* PAYMENT */
        .pay-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            cursor: pointer;
            border-bottom: 1px solid #F5EFE6;
        }

        .pay-option:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .pay-icon {
            font-size: 1.2rem;
        }

        .pay-label {
            flex: 1;
        }

        .pay-label-main {
            font-size: 0.9rem;
            color: #333;
            font-weight: 500;
            display: block;
        }

        .pay-label-sub {
            font-size: 0.75rem;
            color: #999;
            margin-top: 2px;
            display: block;
        }

        .radio-circle {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #DDD;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .radio-circle.selected {
            border-color: #9B1A1A;
        }

        .radio-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #9B1A1A;
            display: none;
        }

        .radio-circle.selected .radio-dot {
            display: block;
        }

        /* QRIS UPLOAD SECTION */
        .qris-section {
            display: none;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px dashed #E8DDD0;
        }

        .qris-section.show {
            display: block;
        }

        .qris-info {
            background: #FFF8EC;
            border: 1.5px solid #FFD080;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 12px;
            font-size: 0.8rem;
            color: #7A5000;
            line-height: 1.5;
        }

        .qris-code-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 14px;
        }

        .qris-placeholder {
            width: 160px;
            height: 160px;
            background: #F5EFE6;
            border-radius: 12px;
            border: 2px dashed #BBAA99;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .qris-placeholder svg {
            width: 48px;
            height: 48px;
            stroke: #BBAA99;
            fill: none;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .qris-placeholder span {
            font-size: 0.7rem;
            color: #BBAA99;
            font-weight: 600;
        }

        .qris-nominal {
            font-size: 0.82rem;
            color: #9B1A1A;
            font-weight: 700;
            text-align: center;
        }

        .upload-label {
            display: block;
            width: 100%;
            padding: 12px 16px;
            background: #F5EFE6;
            border: 2px dashed #BBAA99;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 6px;
        }

        .upload-label:hover {
            background: #EDE4D6;
            border-color: #9B1A1A;
        }

        .upload-label span {
            font-size: 0.85rem;
            color: #9B1A1A;
            font-weight: 600;
        }

        .upload-label input {
            display: none;
        }

        .upload-preview {
            display: none;
            background: #F0FAF0;
            border: 1.5px solid #2ECC71;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.82rem;
            color: #1A7A4A;
            font-weight: 600;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .upload-preview.show {
            display: flex;
        }

        .upload-preview svg {
            width: 16px;
            height: 16px;
            stroke: #2ECC71;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .err-upload {
            font-size: 0.78rem;
            color: #9B1A1A;
            font-weight: 600;
            display: none;
            margin-top: 4px;
        }

        /* SUMMARY */
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

        /* ERR */
        .err-msg {
            font-size: 0.8rem;
            color: #9B1A1A;
            font-weight: 600;
            margin-top: -6px;
            margin-bottom: 8px;
            display: none;
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

        .btn-confirm {
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

        .btn-confirm:hover {
            background: #7f1414;
        }

        .btn-confirm:active {
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
            <button class="back-btn" onclick="window.location.href='{{ route('cart') }}'">
                <svg viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
            </button>
            <h1>Checkout</h1>
        </div>

        <div class="scroll-area">
            <!-- ALAMAT -->
            <div class="section-card">
                <div class="section-title">Alamat Pengiriman</div>
                <input class="field-input" id="alamat" type="text" placeholder="Alamat lengkap kamu...">
                <div class="err-msg" id="err-alamat">Alamat wajib diisi</div>
                <input class="field-input" id="patokan" type="text" placeholder="Patokan (Gedung, Lantai, dll)">
            </div>

            <!-- PEMBAYARAN -->
            <div class="section-card">
                <div class="section-title">Metode Pembayaran</div>

                <div class="pay-option" onclick="selectPay('cod')">
                    <span class="pay-icon"><svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor"
                            fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="7" cy="17" r="2" />
                            <circle cx="17" cy="17" r="2" />
                            <path d="M5 17H3v-6l2-5h9l4 5h3v6h-2" />
                            <path d="M15 6h-6" />
                        </svg></span>
                    <div class="pay-label">
                        <span class="pay-label-main">Cash on Delivery (COD)</span>
                        <span class="pay-label-sub">Bayar tunai saat pesanan tiba</span>
                    </div>
                    <div class="radio-circle selected" id="radio-cod">
                        <div class="radio-dot"></div>
                    </div>
                </div>

                <div class="pay-option" onclick="selectPay('qris')">
                    <span class="pay-icon"><svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor"
                            fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                            <rect x="7" y="7" width="3" height="3" />
                            <rect x="14" y="7" width="3" height="3" />
                            <rect x="7" y="14" width="3" height="3" />
                            <rect x="14" y="14" width="3" height="3" />
                        </svg></span>
                    <div class="pay-label">
                        <span class="pay-label-main">QRIS (Transfer Manual)</span>
                        <span class="pay-label-sub">Scan & unggah bukti transfer</span>
                    </div>
                    <div class="radio-circle" id="radio-qris">
                        <div class="radio-dot"></div>
                    </div>
                </div>

                <!-- QRIS UPLOAD — hanya muncul saat QRIS dipilih -->
                <div class="qris-section" id="qris-section">
                    <div class="qris-info">
                        <svg style="width:14px;height:14px;margin-right:4px;vertical-align:middle" viewBox="0 0 24 24"
                            stroke="#7A5000" fill="none" stroke-width="2">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg> Scan QRIS di bawah, transfer sesuai nominal, lalu unggah bukti transfer. Pesanan akan
                        diverifikasi manual oleh penjual.
                    </div>
                    <div class="qris-code-wrap">
                        <div class="qris-placeholder">
                            <svg viewBox="0 0 24 24">
                                <rect x="2" y="2" width="8" height="8" />
                                <rect x="14" y="2" width="8" height="8" />
                                <rect x="2" y="14" width="8" height="8" />
                                <rect x="14" y="14" width="4" height="4" />
                                <rect x="20" y="14" width="2" height="2" />
                                <rect x="14" y="20" width="6" height="2" />
                            </svg>
                            <span>QRIS TOKO</span>
                        </div>
                        <div class="qris-nominal">Total: <span id="qris-nominal-val">Rp0</span></div>
                    </div>

                    <label class="upload-label" id="upload-label">
                        <span><svg style="width:16px;height:16px;margin-right:4px;vertical-align:middle"
                                viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2">
                                <path
                                    d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" />
                            </svg> Pilih Bukti Transfer (JPG/PNG, maks 2MB)</span>
                        <input type="file" id="bukti-input" accept="image/jpeg,image/png,image/jpg"
                            onchange="handleUpload(this)">
                    </label>
                    <div class="upload-preview" id="upload-preview">
                        <svg viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        <span id="upload-filename">bukti.jpg</span>
                    </div>
                    <div class="err-upload" id="err-upload">Bukti transfer wajib diunggah untuk pembayaran QRIS</div>
                </div>
            </div>

            <!-- RINGKASAN -->
            <div class="section-card">
                <div class="sum-row">
                    <span class="sum-label" id="subtotal-label">Subtotal (0 item)</span>
                    <span class="sum-val" id="subtotal-val">Rp0</span>
                </div>
                <hr class="sum-divider">
                <div class="sum-row">
                    <span class="sum-total-label">Total</span>
                    <span class="sum-total-val" id="total-val">Rp0</span>
                </div>
            </div>
        </div>

        <div class="bottom-bar">
            <button class="btn-confirm" onclick="konfirmasi()">Konfirmasi Pesanan</button>
        </div>
    </div>

    <script>
        function rp(n) { return 'Rp' + n.toLocaleString('id-ID'); }

        let payMethod = 'cod';

        let uploadedFile = null;

        function selectPay(m) {
            payMethod = m;
            document.getElementById('radio-cod').classList.toggle('selected', m === 'cod');
            document.getElementById('radio-qris').classList.toggle('selected', m === 'qris');
            document.getElementById('qris-section').classList.toggle('show', m === 'qris');
            // reset upload error when switching
            document.getElementById('err-upload').style.display = 'none';
        }


        function handleUpload(input) {
            const file = input.files[0];
            if (!file) return;
            // Validate MIME type
            const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowed.includes(file.type)) {
                document.getElementById('err-upload').textContent = 'Format file tidak didukung. Gunakan JPG atau PNG.';
                document.getElementById('err-upload').style.display = 'block';
                input.value = '';
                return;
            }
            // Validate size <= 2MB
            if (file.size > 2 * 1024 * 1024) {
                document.getElementById('err-upload').textContent = 'Ukuran file melebihi 2MB.';
                document.getElementById('err-upload').style.display = 'block';
                input.value = '';
                return;
            }
            uploadedFile = file.name;
            document.getElementById('err-upload').style.display = 'none';
            document.getElementById('upload-preview').classList.add('show');
            document.getElementById('upload-filename').textContent = file.name;
            document.getElementById('upload-label').style.display = 'none';
        }

        // Load cart summary
        const cart = JSON.parse(localStorage.getItem('hc_cart') || '{}');
        const count = Object.values(cart).reduce((s, i) => s + i.qty, 0);
        const total = Object.values(cart).reduce((s, i) => s + (i.basePrice + i.addExtra) * i.qty, 0);

        document.getElementById('subtotal-label').textContent = `Subtotal (${count} item)`;
        document.getElementById('subtotal-val').textContent = rp(total);
        document.getElementById('total-val').textContent = rp(total);
        document.getElementById('qris-nominal-val').textContent = rp(total);

        // Generate order number format HC-YYYYMMDD-XXXX
        function genOrderId() {
            const now = new Date();
            const y = now.getFullYear();
            const m = String(now.getMonth() + 1).padStart(2, '0');
            const d = String(now.getDate()).padStart(2, '0');
            const orders = JSON.parse(localStorage.getItem('hc_orders') || '[]');
            const seq = String(orders.length + 1).padStart(4, '0');
            return `HC-${y}${m}${d}-${seq}`;
        }

        function konfirmasi() {
            const alamat = document.getElementById('alamat').value.trim();
            const errEl = document.getElementById('err-alamat');
            const inp = document.getElementById('alamat');

            if (!alamat) {
                errEl.style.display = 'block';
                inp.classList.add('error');
                inp.focus();
                return;
            }
            errEl.style.display = 'none';
            inp.classList.remove('error');

            // Validasi bukti upload untuk QRIS
            if (payMethod === 'qris' && !uploadedFile) {
                document.getElementById('err-upload').textContent = 'Bukti transfer wajib diunggah untuk pembayaran QRIS';
                document.getElementById('err-upload').style.display = 'block';
                document.getElementById('qris-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            const orderId = genOrderId();

            // Status awal: QRIS = pending_verification, lainnya = new
            const initialStatus = payMethod === 'qris' ? 'pending_verification' : 'new';

            const order = {
                id: orderId,
                alamat: alamat,
                patokan: document.getElementById('patokan').value.trim(),
                pay: payMethod,
                paymentReceipt: uploadedFile || null,
                items: JSON.parse(localStorage.getItem('hc_cart') || '{}'),
                total: total,
                count: count,
                estimasi: '15–20 menit',
                status: initialStatus,
                time: Date.now()
            };

            const orders = JSON.parse(localStorage.getItem('hc_orders') || '[]');
            orders.push(order);
            localStorage.setItem('hc_orders', JSON.stringify(orders));
            localStorage.setItem('hc_last_order', JSON.stringify(order));
            localStorage.removeItem('hc_cart');

            window.location.href = '{{ route('order.success') }}';
        }
    </script>
</body>

</html>