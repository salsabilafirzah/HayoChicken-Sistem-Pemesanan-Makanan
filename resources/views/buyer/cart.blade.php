<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Keranjang</title>
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
            padding: 16px 16px 120px;
        }

        .scroll-area::-webkit-scrollbar {
            display: none;
        }

        /* EMPTY STATE */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            text-align: center;
            gap: 12px;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 4px;
        }

        .empty-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
        }

        .empty-sub {
            font-size: 0.85rem;
            color: #999;
        }

        .btn-back-home {
            margin-top: 12px;
            padding: 12px 28px;
            background: #9B1A1A;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
        }

        /* CART ITEMS */
        .cart-item {
            background: white;
            border-radius: 16px;
            padding: 14px;
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .ci-img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            border-radius: 10px;
            background: #F5EFE6;
            flex-shrink: 0;
        }

        .ci-body {
            flex: 1;
        }

        .ci-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 3px;
        }

        .ci-extra {
            font-size: 0.75rem;
            color: #AAA;
            margin-bottom: 6px;
        }

        .ci-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ci-price {
            font-size: 0.95rem;
            font-weight: 700;
            color: #9B1A1A;
        }

        .ci-qty-row {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .cq-btn {
            width: 28px;
            height: 28px;
            border: none;
            border-radius: 50%;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cq-btn.minus {
            background: #E8DDD0;
            color: #555;
        }

        .cq-btn.plus {
            background: #9B1A1A;
            color: white;
        }

        .cq-num {
            width: 32px;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 700;
            color: #1A1A1A;
        }

        /* SUMMARY */
        .summary-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-top: 8px;
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

        .btn-checkout {
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

        .btn-checkout:hover {
            background: #7f1414;
        }

        .btn-checkout:active {
            transform: scale(0.98);
        }

        .btn-checkout:disabled {
            background: #CCC;
            cursor: default;
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
            <h1>Keranjang</h1>
        </div>

        <div class="scroll-area" id="scroll-area">
            <div id="cart-content"></div>
        </div>

        <div class="bottom-bar">
            <button class="btn-checkout" id="btn-checkout" onclick="goCheckout()">Checkout</button>
        </div>
    </div>

    <script>
        const addonsInfo = [
            { name: 'Chicken Strips', price: 4000 },
            { name: 'Ayam Tambahan', price: 10000 },
            { name: 'Sambal Matah', price: 1000 }
        ];

        function rp(n) { return 'Rp' + n.toLocaleString('id-ID'); }

        let cartItems = [];

        async function apiFetch(url, method = 'GET', body = null) {
            const options = {
                method,
                credentials: 'same-origin',
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
                    window.location.href = '{{ route('login') }}';
                    return null;
                }
                return await response.json();
            } catch (err) {
                console.error("API Error:", err);
                return { success: false, message: "Koneksi bermasalah." };
            }
        }

        async function loadCartData() {
            const res = await apiFetch('/web/cart');
            if (res && res.success) {
                cartItems = res.data;
                render();
            }
        }

        function cartCount() {
            return cartItems.reduce((s, i) => s + i.quantity, 0);
        }

        function cartTotal() {
            return cartItems.reduce((t, item) => {
                const price = item.product ? item.product.base_price : item.price_snapshot;
                return t + (price * item.quantity);
            }, 0);
        }

        function render() {
            const area = document.getElementById('cart-content');
            const btn = document.getElementById('btn-checkout');

            if (cartItems.length === 0) {
                area.innerHTML = `<div class="empty-state">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24" width="64" height="64" stroke="#9B1A1A" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            </div>

            <div class="empty-title">Keranjang Kosong</div>
            <div class="empty-sub">Yuk tambahkan menu favoritmu!</div>
            <button class="btn-back-home" onclick="window.location.href='{{ route('home') }}'">Lihat Menu</button>
        </div>`;
                btn.disabled = true;
                return;
            }

            btn.disabled = false;
            const count = cartCount();
            const total = cartTotal();

            let html = '';
            cartItems.forEach(item => {
                const prod = item.product;
                if (!prod) return;
                html += `<div class="cart-item">
            <img class="ci-img" src="${prod.image_url}" alt="${prod.name}">
            <div class="ci-body">
                <div class="ci-name">${prod.name}</div>
                <div class="ci-foot">
                    <div class="ci-price">${rp(prod.base_price * item.quantity)}</div>
                    <div class="qty-wrap" style="display:flex; align-items:center;">
                        <button class="cq-btn minus" onclick="changeQty(${prod.id}, -1)">−</button>
                        <div class="cq-num">${item.quantity}</div>
                        <button class="cq-btn plus" onclick="changeQty(${prod.id}, 1)">+</button>
                    </div>
                </div>
            </div>
        </div>`;
            });

            html += `<div class="summary-card">
        <div class="sum-row">
            <span class="sum-label">Subtotal (${count} item)</span>
            <span class="sum-val">${rp(total)}</span>
        </div>
        <hr class="sum-divider">
        <div class="sum-row">
            <span class="sum-total-label">Total</span>
            <span class="sum-total-val">${rp(total)}</span>
        </div>
    </div>`;

            area.innerHTML = html;
            btn.textContent = `Checkout (${count} item)`;
        }

        async function changeQty(productId, d) {
            const item = cartItems.find(i => i.product_id == productId);
            if (!item) return;

            const newQty = item.quantity + d;
            if (newQty <= 0) {
                await apiFetch(`/web/cart/${item.id}`, 'DELETE');
            } else {
                await apiFetch(`/web/cart/${item.id}`, 'PATCH', { quantity: newQty });
            }
            await loadCartData();
        }

        function goCheckout() {
            window.location.href = '{{ route('checkout') }}';
        }

        loadCartData();
    </script>
</body>

</html>