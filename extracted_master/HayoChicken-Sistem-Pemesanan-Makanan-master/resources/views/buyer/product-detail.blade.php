<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Detail Menu</title>
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

        /* HEADER */
        .detail-header {
            background: #9B1A1A;
            position: relative;
            flex-shrink: 0;
            height: 300px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        /* decorative circles for depth */
        .detail-header::before {
            content: '';
            position: absolute;
            bottom: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 50%;
        }

        .detail-header::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            width: 210px;
            height: 210px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 50%;
        }

        .header-top {
            position: absolute;
            top: 52px;
            left: 16px;
            right: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .icon-btn {
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

        .icon-btn svg {
            width: 20px;
            height: 20px;
            stroke: white;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .icon-btn.fav-active svg {
            fill: white;
        }

        .food-img-wrap {
            position: relative;
            z-index: 2;
            width: 220px;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: -30px;
        }

        .food-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 16px 32px rgba(0, 0, 0, 0.35));
            animation: floatImg 3s ease-in-out infinite;
        }

        @keyframes floatImg {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        /* CONTENT */
        .scroll-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 100px;
        }

        .scroll-area::-webkit-scrollbar {
            display: none;
        }

        .content-body {
            padding: 40px 20px 16px;
        }

        .prod-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1A1A1A;
            margin-bottom: 8px;
        }

        .prod-desc {
            font-size: 0.88rem;
            color: #888;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .prod-price {
            font-size: 1.35rem;
            font-weight: 700;
            color: #9B1A1A;
            margin-bottom: 20px;
        }

        /* QUANTITY */
        .section-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 10px;
        }

        .qty-wrap {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 24px;
        }

        .qty-btn {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 50%;
            font-size: 1.3rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.1s;
        }

        .qty-btn:active {
            transform: scale(0.9);
        }

        .qty-minus {
            background: #E8DDD0;
            color: #555;
        }

        .qty-plus {
            background: #9B1A1A;
            color: white;
        }

        .qty-num {
            width: 48px;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 700;
            color: #1A1A1A;
        }

        /* TAMBAHAN */
        .tambahan-card {
            background: white;
            border-radius: 16px;
            padding: 4px 0;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .tambahan-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid #F5EFE6;
            cursor: pointer;
        }

        .tambahan-item:last-child {
            border-bottom: none;
        }

        .t-check {
            width: 22px;
            height: 22px;
            border: 2px solid #DDD;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.15s;
        }

        .t-check.checked {
            background: #9B1A1A;
            border-color: #9B1A1A;
        }

        .t-check svg {
            width: 13px;
            height: 13px;
            stroke: white;
            fill: none;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
            display: none;
        }

        .t-check.checked svg {
            display: block;
        }

        .t-label {
            flex: 1;
            font-size: 0.9rem;
            color: #333;
        }

        .t-price {
            font-size: 0.85rem;
            color: #9B1A1A;
            font-weight: 600;
        }

        /* CATATAN */
        .catatan-area {
            background: white;
            border-radius: 12px;
            border: none;
            outline: none;
            padding: 14px 16px;
            width: 100%;
            font-family: inherit;
            font-size: 0.88rem;
            color: #333;
            resize: none;
            min-height: 80px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .catatan-area::placeholder {
            color: #BBAA99;
        }

        /* BOTTOM BUTTON */
        .bottom-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px 20px;
            background: linear-gradient(to top, #F9F4EB 80%, transparent);
            z-index: 100;
        }

        .btn-cart {
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

        .btn-cart:hover {
            background: #7f1414;
        }

        .btn-cart:active {
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
        <div class="detail-header">
            <div class="header-top">
                <button class="icon-btn" onclick="window.location.href='{{ route('home') }}'">
                    <svg viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                </button>
                <button class="icon-btn" id="fav-btn" onclick="toggleFav()">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                </button>
            </div>
            <div class="food-img-wrap">
                <img id="prod-img" src="" alt="Menu">
            </div>
        </div>

        <div class="scroll-area">
            <div class="content-body">
                <div id="prod-name" class="prod-name">—</div>
                <div id="prod-desc" class="prod-desc">—</div>
                <div id="prod-price" class="prod-price">—</div>

                <div class="qty-wrap">
                    <button class="qty-btn qty-minus" onclick="changeQty(-1)">−</button>
                    <div class="qty-num" id="qty-display">1</div>
                    <button class="qty-btn qty-plus" onclick="changeQty(1)">+</button>
                </div>

                <div class="section-label">Tambahan (Opsional)</div>
                <div class="tambahan-card">
                    @forelse($product->productExtras as $index => $extra)
                    <div class="tambahan-item" onclick="toggleAdd({{ $index }})">
                        <div class="t-check" id="add-{{ $index }}"><svg viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg></div>
                        <span class="t-label">{{ $extra->name }}</span>
                        <span class="t-price">+Rp{{ number_format($extra->additional_price, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <div class="tambahan-item">
                        <span class="t-label" style="color: #999;">Tidak ada tambahan tersedia</span>
                    </div>
                    @endforelse
                </div>

                <div class="section-label">Catatan Pesanan</div>
                <textarea class="catatan-area" id="catatan" placeholder="misal: jangan terlalu pedas..."></textarea>
            </div>
        </div>

        <div class="bottom-bar">
            <button class="btn-cart" id="btn-tambah" onclick="addToCartAndGo()">Tambah ke Keranjang - Rp18.000</button>
        </div>
    </div>

    <script>
        const item = {!! json_encode([
            'id' => $product->id,
            'name' => $product->name,
            'desc' => $product->description,
            'price' => $product->base_price,
            'img' => $product->image_url ?: '/assets/fried_chicken.png'
        ]) !!};

        const addons = {!! json_encode($product->productExtras->map(function($e) {
            return ['id' => $e->id, 'name' => $e->name, 'price' => $e->additional_price];
        })) !!};

        const itemId = item.id;

        document.getElementById('prod-name').textContent = item.name;
        document.getElementById('prod-desc').textContent = item.desc;
        document.getElementById('prod-img').src = item.img;

        function rp(n) { return 'Rp' + n.toLocaleString('id-ID'); }

        async function apiFetch(url, method = 'GET', body = null) {
            const options = {
                method,
                credentials: 'same-origin', // WAJIB: kirim session cookie
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            };
            if (body) options.body = JSON.stringify(body);
            
            try {
                const response = await fetch(url, options);
                if (response.status === 401) {
                    console.warn("API 401: Unauthorized.");
                    return null;
                }
                if (!response.ok) {
                    const data = await response.json().catch(() => ({}));
                    console.warn("API Error:", response.status, data.message);
                    return data;
                }
                return await response.json();
            } catch (err) {
                console.error("API Error:", err);
                return { success: false, message: "Koneksi bermasalah." };
            }
        }

        let qty = 1;
        let addChecked = new Array(addons.length).fill(false);
        let isFav = false;

        async function loadInitialState() {
            const res = await apiFetch('/web/favorites');
            if (res && res.success) {
                isFav = res.data.some(f => f.product_id == itemId);
                document.getElementById('fav-btn').classList.toggle('fav-active', isFav);
            }
            updateBtn();
        }

        function calcTotal() {
            const addTotal = addons.reduce((s, a, i) => s + (addChecked[i] ? a.price : 0), 0);
            return (item.price + addTotal) * qty;
        }

        function updateBtn() {
            document.getElementById('btn-tambah').textContent = `Tambah ke Keranjang - ${rp(calcTotal())}`;
            document.getElementById('prod-price').textContent = rp(calcTotal());
        }

        function changeQty(d) {
            qty = Math.max(1, qty + d);
            document.getElementById('qty-display').textContent = qty;
            updateBtn();
        }

        function toggleAdd(i) {
            addChecked[i] = !addChecked[i];
            const el = document.getElementById('add-' + i);
            if (el) el.classList.toggle('checked', addChecked[i]);
            updateBtn();
        }

        async function toggleFav() {
            const res = await apiFetch('/web/favorites/toggle', 'POST', {
                product_id: itemId
            });
            if (res && res.success) {
                isFav = !isFav;
                document.getElementById('fav-btn').classList.toggle('fav-active', isFav);
                
                // Sesuai permintaan user: langsung buka page favorit
                window.location.href = '{{ route('home') }}?v=fav';
            }
        }

        async function addToCartAndGo() {
            const selectedExtras = addons.filter((a, i) => addChecked[i]).map(a => a.name); // Simpan nama extras, bukan ID (sesuai logika controller)
            const notes = document.getElementById('catatan').value;

            const res = await apiFetch('/web/cart', 'POST', {
                product_id: itemId,
                quantity: qty,
                selected_extras_snapshot: selectedExtras,
                note: notes
            });

            if (res && res.success) {
                window.location.href = '{{ route('home') }}?v=cart'; // Arahkan ke cart view di home
            } else {
                alert(res ? (res.message || "Gagal menambah ke keranjang.") : "Gagal menambah ke keranjang. Pastikan sudah login.");
            }
        }

        loadInitialState();
        updateBtn(); // Tambahkan ini agar harga awal dirender
    </script>
</body>

</html>