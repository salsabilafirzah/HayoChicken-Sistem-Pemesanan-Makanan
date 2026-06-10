<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Beranda</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* =============================================
           RESET & BASE
        ============================================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: #9e090f;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* =============================================
           APP SHELL — same as index.html (420px x 850px)
        ============================================= */
        .app {
            width: 100%;
            max-width: 420px;
            height: 100vh;
            height: 100dvh;
            background: #F9F4EB;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            /* padding-bottom removed to allow full height views */
        }

        /* =============================================
           VIEWS
        ============================================= */
        .view {
            display: none;
            flex-direction: column;
            flex: 1;
            overflow: hidden;
            position: relative;
        }

        .view.active {
            display: flex;
        }

        .scroll-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 80px;
        }

        .scroll-area::-webkit-scrollbar {
            display: none;
        }

        /* =============================================
           NAVBAR
        ============================================= */
        .navbar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 72px;
            background: #D8CBBB;
            border-top-left-radius: 26px;
            border-top-right-radius: 26px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 0 18px;
            z-index: 300;
        }

        .nav-btn {
            width: 48px;
            height: 48px;
            border: none;
            background: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #8B7A6A;
            position: relative;
            transition: all 0.2s ease;
        }

        .nav-btn.active {
            background: #9B1A1A;
            color: white;
        }

        .nav-btn svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .nav-btn.active svg {
            stroke: white;
        }

        .cart-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            min-width: 18px;
            height: 18px;
            background: #F5A623;
            color: white;
            border-radius: 9px;
            font-size: 10px;
            font-weight: 700;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            line-height: 1;
        }

        .cart-badge.show {
            display: flex;
        }

        /* =============================================
           HOME VIEW — HEADER
        ============================================= */
        .home-header {
            background: #9B1A1A;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            padding: 48px 20px 22px;
            flex-shrink: 0;
        }

        .hdr-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 16px;
        }

        .hdr-greet {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.9rem;
            margin-bottom: 3px;
        }

        .hdr-name {
            color: white;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .bell-btn {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
        }

        .bell-badge {
            position: absolute;
            top: 10px;
            right: 12px;
            width: 10px;
            height: 10px;
            background: #FF4757;
            border: 2px solid #9B1A1A;
            border-radius: 50%;
            display: none;
        }

        .bell-badge.show {
            display: block;
        }

        .bell-btn svg {
            width: 20px;
            height: 20px;
            stroke: white;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .search-bar {
            background: rgba(255, 255, 255, 0.93);
            border-radius: 50px;
            display: flex;
            align-items: center;
            padding: 11px 16px;
            gap: 10px;
        }

        .search-bar svg {
            width: 18px;
            height: 18px;
            stroke: #9B1A1A;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .search-bar input {
            border: none;
            outline: none;
            background: none;
            font-family: inherit;
            font-size: 0.9rem;
            color: #333;
            width: 100%;
        }

        .search-bar input::placeholder {
            color: #BBAA99;
        }

        /* =============================================
           HOME — CATEGORIES
        ============================================= */
        .cat-section {
            padding: 20px 0 0;
        }

        .cat-scroll {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            padding: 4px 16px 8px;
        }

        .cat-scroll::-webkit-scrollbar {
            display: none;
        }

        .cat-item {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .cat-box {
            width: 110px;
            height: 110px;
            background: #EBE0D0;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: transform 0.15s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .cat-box:hover {
            transform: scale(0.96);
        }

        .cat-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cat-box .c-icon {
            width: 44px;
            height: 44px;
            color: #9B1A1A;
        }

        .cat-name {
            font-size: 0.78rem;
            color: #555;
            font-weight: 500;
        }

        /* =============================================
           HOME — BANNER
        ============================================= */
        .banner-section {
            padding: 16px 16px 0;
        }

        .banner-scroll {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding-bottom: 10px;
        }

        .banner-scroll::-webkit-scrollbar {
            display: none;
        }

        .banner {
            flex: 0 0 100%;
            scroll-snap-align: center;
            border-radius: 20px;
            padding: 20px 0px 20px 20px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            height: 140px;
        }

        .banner-left {
            flex: 1;
            z-index: 2;
        }

        .b-new {
            color: white;
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 5px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
        }

        .b-sub {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.78rem;
            line-height: 1.45;
            margin-bottom: 12px;
        }

        .b-btn {
            display: inline-block;
            background: white;
            color: #C8930D;
            border: none;
            border-radius: 50px;
            padding: 8px 18px;
            font-size: 0.78rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
        }

        .banner-img {
            width: 110px;
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            flex-shrink: 0;
            position: relative;
            z-index: 2;
        }

        .banner-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .banner-dots {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 5px;
            z-index: 2;
        }

        .bdot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.45);
        }

        .bdot.on {
            background: white;
            width: 16px;
            border-radius: 3px;
        }

        /* =============================================
           HOME — MENU POPULER
        ============================================= */
        .populer-section {
            padding: 20px 16px 0;
        }

        .sec-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .sec-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1A1A1A;
        }

        .lihat-btn {
            background: #FFD5C8;
            color: #9B1A1A;
            border: none;
            border-radius: 50px;
            padding: 7px 16px;
            font-size: 0.78rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
        }

        /* =============================================
           PRODUCT CARD
        ============================================= */
        .prod-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding-bottom: 8px;
        }

        .prod-card {
            background: #EBE0D0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
            position: relative;
            cursor: pointer;
        }

        /* Wavy Red top section */
        .prod-top {
            background-color: #EBE0D0;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' preserveAspectRatio='none'><path d='M0,0 L100,0 L100,45 C65,40 35,85 0,65 Z' fill='%237A1A1A'/></svg>");
            background-size: 100% 100%;
            height: 115px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: visible;
        }

        .prod-top::after {
            display: none;
        }

        /* Food image/emoji — sits on top of wave (z-index above ::after) */
        .prod-food {
            position: relative;
            z-index: 2;
            margin-top: 5px;
            width: 100%;
            height: 115px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .prod-food img {
            width: 138px !important;
            height: 138px !important;
            object-fit: contain;
            filter: drop-shadow(0 8px 10px rgba(0, 0, 0, 0.25));
        }

        .prod-food .f-icon {
            width: 60px;
            height: 60px;
            color: #9B1A1A;
            opacity: 0.3;
        }


        /* Heart button top-right */
        .heart-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 3;
            width: 28px;
            height: 28px;
            background: rgba(255, 255, 255, 0.88);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .heart-btn svg {
            width: 16px;
            height: 16px;
            stroke: #999;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: all 0.2s;
            margin-top: 2px;
        }

        .heart-btn.liked svg {
            stroke: #9B1A1A;
            fill: #9B1A1A;
        }

        .prod-bottom {
            padding: 8px 10px 10px;
        }

        .prod-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .prod-desc {
            font-size: 0.71rem;
            color: #BBAA99;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .prod-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .prod-price {
            font-size: 0.9rem;
            font-weight: 700;
            color: #9B1A1A;
        }

        .plus-btn {
            width: 26px;
            height: 26px;
            background: #9B1A1A;
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 1.15rem;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.1s;
        }

        .plus-btn:active {
            transform: scale(0.88);
        }

        /* =============================================
           CART VIEW
        ============================================= */
        .cart-hdr {
            background: #9B1A1A;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            padding: 48px 20px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        .back-circ {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.25);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        .back-circ svg {
            width: 18px;
            height: 18px;
            stroke: white;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .cart-title {
            color: white;
            font-size: 1.15rem;
            font-weight: 700;
        }

        .bell-btn svg {
            width: 20px;
            height: 20px;
            stroke: white;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* CONFIRMATION OVERLAY */
        .ovl-confirm {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(6px);
        }

        .ovl-confirm.show {
            display: flex;
        }

        .confirm-card {
            background: white;
            border-radius: 28px;
            width: 100%;
            max-width: 340px;
            padding: 32px 24px;
            text-align: center;
            animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .c-icon-box {
            width: 70px;
            height: 70px;
            background: #FFF0F0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #9B1A1A;
        }

        .c-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1A1A1A;
            margin-bottom: 10px;
        }

        .c-text {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.5;
            margin-bottom: 28px;
        }

        .c-btns {
            display: flex;
            gap: 12px;
        }

        .c-btn {
            flex: 1;
            padding: 14px;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
        }

        .c-btn-no {
            background: #F5F5F5;
            color: #666;
            border: none;
        }

        .c-btn-yes {
            background: #9B1A1A;
            color: white;
            border: none;
        }

        .c-btn:active {
            transform: scale(0.95);
        }

        /* PROMO OVERLAY */
        .promo-ovl {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(4px);
        }

        .promo-ovl.show {
            display: flex;
        }

        .promo-card {
            background: white;
            border-radius: 28px;
            width: 100%;
            max-width: 320px;
            padding: 32px 24px;
            text-align: center;
            animation: pop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes pop {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .p-icon-box {
            width: 80px;
            height: 80px;
            background: #FFF8EC;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #F5A623;
        }

        .p-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #1A1A1A;
            margin-bottom: 8px;
        }

        .p-text {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .p-btn-ok {
            width: 100%;
            background: #9B1A1A;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
        }

        .p-btn-row {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .p-btn-sec {
            flex: 1;
            background: #F5F5F5;
            color: #666;
            border: none;
            border-radius: 50px;
            padding: 14px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
        }

        .p-btn-pri {
            flex: 1.2;
            background: #9B1A1A;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
        }

        /* AVAILABLE PROMOS */
        .avail-hdr {
            font-size: 0.9rem;
            font-weight: 800;
            color: #1A1A1A;
            margin: 20px 0 12px;
        }

        .avail-card {
            background: white;
            border: 1.5px solid #F0F0F0;
            border-radius: 14px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .avail-ic {
            color: #9B1A1A;
            display: flex;
            opacity: 0.6;
        }

        .avail-info {
            flex: 1;
        }

        .avail-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1A1A1A;
        }

        .avail-sub {
            font-size: 0.72rem;
            color: #888;
        }

        .avail-btn {
            background: #9B1A1A;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 6px 14px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
        }

        /* COUPON IN CART */
        .coupon-row {
            background: #FFF8EC;
            border: 1.5px dashed #F5A623;
            border-radius: 14px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .cp-ic {
            color: #F5A623;
            display: flex;
        }

        .cp-info {
            flex: 1;
        }

        .cp-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #7A5000;
        }

        .cp-sub {
            font-size: 0.72rem;
            color: #B2822A;
            margin-top: 1px;
        }

        .cp-del {
            color: #9B1A1A;
            font-size: 0.75rem;
            font-weight: 700;
            background: none;
            border: none;
            cursor: pointer;
        }

        /* Cart scroll area needs extra bottom padding for checkout bar */
        .cart-scroll {
            padding-bottom: 100px !important;
        }

        .cart-inner {
            padding: 16px 16px 0;
        }

        /* Select all row */
        .sel-row {
            background: white;
            border-radius: 14px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .chk {
            width: 22px;
            height: 22px;
            background: #9B1A1A;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            cursor: pointer;
        }

        .chk svg {
            width: 12px;
            height: 12px;
            stroke: white;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sel-lbl {
            font-size: 0.9rem;
            font-weight: 600;
            color: #222;
            flex: 1;
        }

        .itm-cnt {
            font-size: 0.8rem;
            color: #BBAA99;
            margin-right: 4px;
        }

        .hapus-btn {
            color: #9B1A1A;
            font-size: 0.8rem;
            font-weight: 700;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        /* Cart item */
        .cart-item {
            background: white;
            border-radius: 14px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .ci-img {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: #EBE0D0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .ci-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .ci-img .f-icon {
            width: 32px;
            height: 32px;
            color: #9B1A1A;
            opacity: 0.3;
        }

        .ci-info {
            flex: 1;
        }

        .ci-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 4px;
        }

        .ci-price {
            font-size: 0.9rem;
            color: #9B1A1A;
            font-weight: 600;
        }

        .qty-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .q-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            background: #F9F4EB;
            color: #444;
            font-size: 1.05rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .q-btn.qplus {
            background: #9B1A1A;
            color: white;
        }

        .q-num {
            font-size: 0.9rem;
            font-weight: 700;
            min-width: 18px;
            text-align: center;
        }

        /* Cart summary */
        .cart-sum {
            background: white;
            border-radius: 14px;
            padding: 14px 16px;
            margin-top: 6px;
        }

        .sum-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #888;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 1.5px dashed #E0D4C4;
        }

        .tot-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .tot-lbl {
            color: #1A1A1A;
        }

        .tot-val {
            color: #9B1A1A;
        }

        /* CHECKOUT BAR — Fixed at bottom */
        .checkout-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px 20px 16px; /* Sejajar dengan area navbar yang sekarang kosong */
            background: linear-gradient(to top, #F9F4EB 80%, transparent);
            z-index: 400;
        }

        .checkout-btn {
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
            box-shadow: 0 8px 24px rgba(155, 26, 26, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .checkout-btn:hover {
            background: #7f1414;
        }

        .checkout-btn:active {
            transform: scale(0.98);
        }

        .checkout-btn:disabled {
            background: #CCC;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* Checkout button — absolute at bottom (no navbar on cart view) */
        /* CHECKOUT OVERLAY */
        .ovl-checkout {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            display: none;
            align-items: flex-end;
            justify-content: center;
            backdrop-filter: blur(8px);
        }

        .ovl-checkout.show {
            display: flex;
        }

        .chk-card {
            background: #F9F4EB;
            width: 100%;
            max-width: 420px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            padding: 32px 24px;
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        .chk-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1A1A1A;
            margin-bottom: 24px;
            text-align: center;
        }

        .chk-label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
            color: #666;
            font-size: 0.9rem;
        }

        .chk-input {
            width: 100%;
            padding: 14px;
            border: 2px solid #E0D5C5;
            border-radius: 16px;
            background: white;
            font-size: 1rem;
            margin-bottom: 20px;
            font-family: inherit;
        }

        .pay-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .pay-opt {
            padding: 16px 8px;
            border: 2px solid #E0D5C5;
            border-radius: 16px;
            background: white;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .pay-opt.active {
            border-color: #9B1A1A;
            background: #FDF2F2;
        }

        .pay-icon {
            font-size: 1.5rem;
            margin-bottom: 4px;
            display: block;
        }

        .pay-name {
            font-weight: 700;
            font-size: 0.8rem;
        }

        .receipt-sec {
            background: #FFF;
            border: 2px dashed #9B1A1A;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            margin-bottom: 24px;
            cursor: pointer;
        }

        .receipt-info {
            color: #9B1A1A;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .chk-foot {
            display: flex;
            gap: 12px;
        }

        .btn-cancel {
            flex: 1;
            padding: 16px;
            border-radius: 50px;
            border: 2px solid #E0D5C5;
            background: white;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-confirm {
            flex: 2;
            padding: 16px;
            border-radius: 50px;
            background: #9B1A1A;
            color: white;
            border: none;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(155, 26, 26, 0.3);
        }

        /* Empty state */
        .empty-state {
            padding: 80px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            color: #555; /* Warna lebih gelap agar kontras */
            text-align: center;
            background: transparent;
        }

        .empty-state .e-icon {
            font-size: 5rem;
            color: #9B1A1A; /* Warna merah chicken */
            opacity: 0.2;
            margin-bottom: 10px;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1A1A1A;
            margin: 0;
        }

        .empty-state p {
            font-size: 0.9rem;
            color: #888;
            max-width: 250px;
            line-height: 1.5;
        }

        /* Animations */
        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.4); }
            100% { transform: scale(1); }
        }
        .pop-anim {
            animation: pop 0.3s ease-out;
        }

        /* =============================================
           FAVORIT VIEW
        ============================================= */
        .fav-hdr {
            background: #9B1A1A;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            padding: 48px 20px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        .fav-body {
            padding: 18px 16px 0;
        }

        .fav-item {
            background: white;
            border-radius: 16px;
            padding: 12px 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .fi-img {
            width: 74px;
            height: 74px;
            border-radius: 12px;
            background: #EBE0D0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .fi-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .fi-img .f-icon {
            width: 36px;
            height: 36px;
            color: #9B1A1A;
            opacity: 0.3;
        }

        .fi-info {
            flex: 1;
        }

        .fi-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 4px;
        }

        .fi-desc {
            font-size: 0.73rem;
            color: #AAA;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .fi-price {
            font-size: 0.9rem;
            font-weight: 700;
            color: #9B1A1A;
        }

        .trash-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: none;
            color: #9B1A1A;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .trash-btn svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* =============================================
           PROFILE VIEW
        ============================================= */
        .prof-hdr {
            background: #9B1A1A;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            padding: 44px 24px 36px;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }

        .avatar {
            width: 80px;
            height: 80px;
            background: #F5A623;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .avatar svg {
            width: 44px;
            height: 44px;
            stroke: #9B1A1A;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .prof-name {
            color: white;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .prof-email {
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.9rem;
        }

        .prof-body {
            padding: 22px 16px;
        }

        .pmenu {
            background: white;
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: background 0.15s;
        }

        .pmenu:hover {
            background: #f9f6f2;
        }

        .pm-icon {
            width: 40px;
            height: 40px;
            background: #EBE0D0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6A5A4A;
            flex-shrink: 0;
        }

        .pm-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .pm-lbl {
            flex: 1;
            font-size: 0.9rem;
            font-weight: 500;
            color: #1A1A1A;
        }

        .pm-lbl.red {
            color: #9B1A1A;
            font-weight: 600;
        }

        .pm-arr svg {
            width: 15px;
            height: 15px;
            stroke: #CCC;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .pm-arr.red svg {
            stroke: #9B1A1A;
        }

        /* =============================================
           DESKTOP — sama persis dengan halaman lain
        ============================================= */
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

        /* --- NOTIFICATIONS --- */
        .ovl-noti {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1500;
            display: none;
            align-items: flex-start;
            justify-content: center;
            padding-top: 80px;
            backdrop-filter: blur(4px);
        }

        .ovl-noti.show {
            display: flex;
        }

        .noti-card {
            background: #F9F4EB;
            width: 90%;
            max-width: 380px;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .noti-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .noti-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1A1A1A;
        }

        .noti-close {
            font-size: 0.9rem;
            color: #9B1A1A;
            font-weight: 700;
            cursor: pointer;
        }

        .noti-item {
            background: white;
            border-radius: 16px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: transform 0.1s;
            border: 1px solid #EEE;
        }

        .noti-item:active {
            transform: scale(0.98);
        }

        .noti-icon {
            width: 44px;
            height: 44px;
            background: #FDF2F2;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9B1A1A;
        }

        .noti-info {
            flex: 1;
        }

        .noti-text {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 2px;
        }

        .noti-status {
            font-size: 0.8rem;
            color: #27ae60;
            font-weight: 600;
        }

        .noti-empty {
            text-align: center;
            padding: 30px 10px;
            color: #888;
            font-size: 0.9rem;
        }

        /* --- ACTIVE ORDER BAR --- */
        .active-order-bar {
            position: absolute;
            bottom: 85px;
            left: 20px;
            right: 20px;
            background: #9B1A1A;
            color: white;
            padding: 14px 18px;
            border-radius: 16px;
            display: none;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 24px rgba(155, 26, 26, 0.3);
            z-index: 100;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .active-order-bar:active {
            transform: scale(0.97);
        }

        .aob-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .aob-text {
            flex: 1;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .aob-text b {
            display: block;
            font-size: 0.95rem;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="app">

        <!-- ================================================
         BERANDA VIEW
    ================================================ -->
        <div class="view active" id="view-home">
            <!-- Header merah -->
            <div class="home-header">
                <div class="hdr-top">
                    <div>
                        <p class="hdr-greet">Halo, selamat datang</p>
                        <div class="hdr-name">{{ Auth::user()->name ?? 'Hayo Guest' }}</div>
                    </div>
                    <button class="bell-btn" onclick="toggleNoti()">
                        <svg viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                        </svg>
                        <div class="bell-badge" id="bell-badge"></div>
                    </button>
                </div>
                <form action="{{ route('home') }}" method="GET" class="search-bar">
                    <svg viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" name="search" placeholder="Lagi mau mam apa?" value="{{ request('search') }}" onchange="this.form.submit()">
                </form>
            </div>

            <div class="scroll-area">
                <!-- Kategori -->
                <div class="cat-section">
                    <div class="cat-scroll">
                        @foreach($categories as $category)
                        <div class="cat-item {{ request('category') == $category->slug ? 'active' : '' }}" 
                             onclick="window.location.href='{{ route('home', ['category' => $category->slug]) }}'">
                            <div class="cat-box">
                                <img src="{{ $category->icon_name ? asset('assets/' . $category->icon_name . '.png') : asset('assets/fried_chicken.png') }}" 
                                     style="width:85px;height:85px;object-fit:contain;">
                            </div>
                            <span class="cat-name">{{ $category->name }}</span>
                        </div>
                        @endforeach
                        @if(request('category') || request('search'))
                        <div class="cat-item" onclick="window.location.href='{{ route('home') }}'">
                            <div class="cat-box" style="background: #eee;">
                                <svg viewBox="0 0 24 24" width="30" height="30" stroke="#666" fill="none" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </div>
                            <span class="cat-name">Reset</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Banner Carousel -->
                <div class="banner-section">
                    <div class="banner-scroll" id="bannerCarousel">

                        <!-- Banner 1: Best Seller (Oranye) -->
                        <div style="flex:0 0 100%; scroll-snap-align:center; border-radius:20px; overflow:hidden; position:relative; height:140px; background:linear-gradient(135deg,#FF7B00,#D46128); display:flex; align-items:center; padding:20px; gap:0; box-shadow:0 6px 18px rgba(200,80,0,0.35); cursor:pointer;"
                            onclick="window.location.href='{{ url('/produk') }}/5'">
                            <div style="flex:1; z-index:2;">
                                <div class="b-new">Best Seller</div>
                                <div class="b-sub">Ayam geprek + sambal<br>matah</div>
                                <button class="b-btn" style="color:#D46128;">Pesan Sekarang</button>

                            </div>
                            <img src="{{ asset('assets/ayam_geprek.png') }}"
                                style="position:absolute; right:-35px; bottom:-55px; width:258px; height:258px; object-fit:contain; filter:drop-shadow(0 6px 10px rgba(0,0,0,0.3)); z-index:2;">
                            <div
                                style="position:absolute; bottom:10px; left:50%; transform:translateX(-50%); display:flex; gap:5px; z-index:3;">
                                <div style="width:18px;height:5px;border-radius:3px;background:white;"></div>
                                <div style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,0.4);">
                                </div>
                                <div style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,0.4);">
                                </div>
                            </div>
                        </div>

                        <!-- Banner 2: New Menu (Kuning) -->
                        <div style="flex:0 0 100%; scroll-snap-align:center; border-radius:20px; overflow:hidden; position:relative; height:140px; background:linear-gradient(135deg,#F0C830,#C8930D); display:flex; align-items:center; padding:20px; gap:0; box-shadow:0 6px 18px rgba(200,140,0,0.35); cursor:pointer;"
                            onclick="window.location.href='{{ url('/produk') }}/6'">
                            <div style="flex:1; z-index:2;">
                                <div class="b-new">New Menu</div>
                                <div class="b-sub">Mie jebew ayam saus<br>keju</div>
                                <button class="b-btn" style="color:#C8930D;">Coba Sekarang</button>

                            </div>
                            <img src="{{ asset('assets/mie_jebew.png') }}"
                                style="position:absolute; right:-55px; bottom:-75px; width:314px; height:314px; object-fit:contain; filter:drop-shadow(0 6px 10px rgba(0,0,0,0.2)); z-index:2;">
                            <div
                                style="position:absolute; bottom:10px; left:50%; transform:translateX(-50%); display:flex; gap:5px; z-index:3;">
                                <div style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,0.4);">
                                </div>
                                <div style="width:18px;height:5px;border-radius:3px;background:white;"></div>
                                <div style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,0.4);">
                                </div>
                            </div>
                        </div>

                        <!-- Banner 3: Promo Jumat (Hijau) -->
                        <div style="flex:0 0 100%; scroll-snap-align:center; border-radius:20px; overflow:hidden; position:relative; height:140px; background:linear-gradient(135deg,#2ecc71,#178c43); display:flex; align-items:center; padding:20px; gap:0; box-shadow:0 6px 18px rgba(20,130,60,0.35); cursor:pointer;"
                            onclick="window.location.href='{{ url('/produk') }}/4'">
                            <div style="flex:1; z-index:2;">
                                <div class="b-new">Promo Jumat</div>
                                <div class="b-sub">Free es teh setiap<br>hari jumat</div>
                                <button class="b-btn" style="color:#178c43;"
                                    onclick="claimPromo(4, 2); event.stopPropagation();">Klaim Promo</button>


                            </div>
                            <img src="{{ asset('assets/three_lemon_teas.png') }}"
                                style="position:absolute; right:-20px; bottom:-50px; width:234px; height:234px; object-fit:contain; filter:drop-shadow(0 6px 10px rgba(0,0,0,0.2)); z-index:2;">
                            <div
                                style="position:absolute; bottom:10px; left:50%; transform:translateX(-50%); display:flex; gap:5px; z-index:3;">
                                <div style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,0.4);">
                                </div>
                                <div style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,0.4);">
                                </div>
                                <div style="width:18px;height:5px;border-radius:3px;background:white;"></div>
                            </div>
                        </div>

                    </div>
                </div>


                <!-- Menu Populer -->
                <div class="populer-section">
                    <div class="sec-head">
                        <span class="sec-title">Menu Populer</span>
                    <button class="lihat-btn" id="btn-see-all" onclick="toggleSeeAll()">Lihat Semua</button>
                    </div>
                    <div class="prod-grid" id="prod-grid">
                        <!-- Dirender oleh JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================
         KERANJANG VIEW
    ================================================ -->
        <div class="view" id="view-cart">
            <div class="cart-hdr">
                <button class="back-circ" onclick="switchView('home')">
                    <svg viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                </button>
                <span class="cart-title">Keranjang</span>
            </div>

            <!-- Scroll area untuk item keranjang -->
            <div class="scroll-area cart-scroll">
                <div class="cart-inner" id="cart-inner">
                    <!-- Dirender oleh JS -->
                </div>
            </div>

            <!-- Checkout button di bawah scroll -->
            <div class="checkout-bar" id="checkout-bar" style="display:none; padding-bottom: 10px;">
                <input type="file" id="qris-upload" style="display:none" accept="image/*">
                <button class="checkout-btn" id="checkout-btn" onclick="doCheckout()">Checkout</button>
            </div>
        </div>



        <!-- ================================================
         FAVORIT VIEW
    ================================================ -->
        <div class="view" id="view-fav">
            <div class="fav-hdr">
                <button class="back-circ" onclick="switchView('home')">
                    <svg viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                </button>
                <span class="cart-title">Favorit</span>
            </div>
            <div class="scroll-area">
                <div class="fav-body" id="fav-body">
                    <!-- Dirender oleh JS -->
                </div>
            </div>
        </div>

        <!-- ================================================
         PROFILE VIEW
    ================================================ -->
        <div class="view" id="view-profile">
            <div class="prof-hdr">
                <div class="avatar">
                    @if(Auth::user()->profile_image_url)
                        <img src="{{ Auth::user()->profile_image_url }}" alt="Profile" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                    @else
                        <svg viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    @endif
                </div>
                <div class="prof-name">{{ Auth::user()->name }}</div>
                <div class="prof-email">{{ Auth::user()->email }}</div>
            </div>
            <div class="scroll-area">
                <div class="prof-body">

                    <div class="pmenu" onclick="window.location.href='{{ route('order.history') }}'">
                        <div class="pm-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                            </svg>
                        </div>
                        <span class="pm-lbl">Riwayat Pesanan</span>
                        <div class="pm-arr"><svg viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6" />
                            </svg></div>
                    </div>

                    <div class="pmenu" onclick="window.location.href='{{ route('address.saved') }}'">
                        <div class="pm-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <span class="pm-lbl">Alamat Tersimpan</span>
                        <div class="pm-arr"><svg viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6" />
                            </svg></div>
                    </div>

                    <div class="pmenu" onclick="window.location.href='{{ route('order.active') }}'">
                        <div class="pm-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                        </div>
                        <span class="pm-lbl">Pesanan Aktif</span>
                        <div class="pm-arr"><svg viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6" />
                            </svg></div>
                    </div>

                    <div class="pmenu" onclick="window.location.href='{{ route('notifications') }}'">
                        <div class="pm-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                            </svg>
                        </div>
                        <span class="pm-lbl">Notifikasi</span>
                        <div class="pm-arr"><svg viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6" />
                            </svg></div>
                    </div>

                    <div class="pmenu" onclick="window.location.href='{{ route('password.change') }}'">
                        <div class="pm-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </div>
                        <span class="pm-lbl">Ubah Password</span>
                        <div class="pm-arr"><svg viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6" />
                            </svg></div>
                    </div>

                    <div class="pmenu" onclick="confirmLogout()">
                        <div class="pm-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                        </div>
                        <span class="pm-lbl red">Keluar</span>
                        <div class="pm-arr red"><svg viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6" />
                            </svg></div>
                    </div>

                </div>
            </div>
        </div>

        <!-- NOTIFICATION OVERLAY -->
        <div class="ovl-noti" id="ovl-noti" onclick="toggleNoti()">
            <div class="noti-card" onclick="event.stopPropagation()">
                <div class="noti-head">
                    <div class="noti-title">Pesananku</div>
                    <div class="noti-close" onclick="toggleNoti()">Tutup</div>
                </div>
                <div id="noti-list">
                    <!-- Dinamis via JS -->
                    <div class="noti-empty">Memuat pesanan...</div>
                </div>
            </div>
        </div>

        <!-- FLOATING ACTIVE ORDER BAR -->
        <div class="active-order-bar" id="active-order-bar" onclick="toggleNoti()">
            <div class="aob-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="white" fill="none" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="aob-text">
                <span id="aob-count">1 Pesanan Aktif</span>
                <b>Pantau Status Pesananmu</b>
            </div>
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="white" fill="none" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </div>

        <!-- ================================================
         NAVBAR BAWAH
    ================================================ -->
        <nav class="navbar">
            <!-- Beranda -->
            <button class="nav-btn active" id="nav-home" onclick="switchView('home')">
                <svg viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
            </button>
            <!-- Keranjang -->
            <button class="nav-btn" id="nav-cart" onclick="switchView('cart')">
                <svg viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                </svg>
                <span class="cart-badge" id="cart-badge">0</span>
            </button>
            <!-- Favorit -->
            <button class="nav-btn" id="nav-fav" onclick="switchView('fav')">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                </svg>
            </button>
            <!-- Profile -->
            <button class="nav-btn" id="nav-profile" onclick="switchView('profile')">
                <svg viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </button>
        </nav>
        <div class="promo-ovl" id="promo-ovl">
            <div class="promo-card">
                <div class="p-icon-box">
                    <svg viewBox="0 0 24 24" width="40" height="40" stroke="currentColor" fill="none"
                        stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>
                <div class="p-title">Promo Berhasil!</div>
                <div class="p-text" id="promo-text">Item promo telah ditambahkan ke koleksi kupon kamu.</div>
                <div class="p-btn-row">
                    <button class="p-btn-sec" onclick="closePromo()">Lanjut Belanja</button>
                    <button class="p-btn-pri" onclick="closePromo('cart')">Lihat Keranjang</button>
                </div>
            </div>
        </div>
        <div class="ovl-confirm" id="ovl-profile">
            <div class="confirm-card" style="max-width: 380px;">
                <div class="p-title">Edit Profil</div>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="form-profile">
                    @csrf
                    <div style="margin-bottom: 16px; text-align: left;">
                        <label style="display:block; font-size: 0.8rem; color: #888; margin-bottom: 6px;">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" style="width:100%; padding: 12px; border: 1.5px solid #E8DDD0; border-radius: 10px; font-family: inherit;">
                    </div>
                    <div style="margin-bottom: 20px; text-align: left;">
                        <label style="display:block; font-size: 0.8rem; color: #888; margin-bottom: 6px;">Foto Profil</label>
                        <input type="file" name="profile_image" accept="image/*" style="width:100%; font-size: 0.8rem;">
                    </div>
                    <div class="p-btn-row">
                        <button type="button" class="p-btn-sec" onclick="closeEditProfile()">Batal</button>
                        <button type="submit" class="p-btn-pri">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="ovl-confirm" id="ovl-confirm">
            <div class="confirm-card">
                <div class="c-icon-box">
                    <svg viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" fill="none"
                        stroke-width="2.5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div class="c-title" id="confirm-title">Konfirmasi</div>
                <div class="c-text" id="confirm-text">Apakah kamu yakin ingin melanjutkan tindakan ini?</div>
                <div class="confirm-btns">
                    <button class="c-btn c-btn-no" onclick="closeConfirm()">Batal</button>
                    <button class="c-btn c-btn-yes" id="confirm-yes-btn">Ya, Lanjutkan</button>
                </div>
        </div>
    </div>

    <script>
        /* ==================================================
           DATA MENU — ganti emoji dengan <img> ketika foto sudah ada
           Contoh: ganti { emoji: '🍗' } dengan { img: '/assets/ayam-goreng.jpg' }
        ================================================== */
        const menu = @json($products);

        /* ==================================================
           STATE (Server-Synchronized)
        ================================================== */
        let cartItems = []; // Array of objects matching CartItem model
        let favoriteIds = new Set(); // Set of product IDs

        /* ==================================================
           UTILITIES
        ================================================== */
        async function apiFetch(url, method = 'GET', body = null) {
            const options = {
                method,
                credentials: 'same-origin', // WAJIB: kirim session cookie Laravel
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
                    console.warn("API 401: Unauthorized. Pastikan sudah login.");
                    return null;
                }
                return await response.json();
            } catch (err) {
                console.error("API Error:", err);
                return null;
            }
        }

        /* ==================================================
           UTILITIES
        ================================================== */
        function rp(n) {
            return 'Rp' + n.toLocaleString('id-ID');
        }
        function cartCount() {
            return cartItems.reduce((a, b) => a + b.quantity, 0);
        }
        function cartTotal() {
            return cartItems.reduce((t, item) => {
                const price = item.product ? item.product.base_price : item.price_snapshot;
                return t + (price * item.quantity);
            }, 0);
        }

        /* gambar makanan: pakai <img> jika ada, fallback emoji */
        function foodHtml(item, size) {
            const imgUrl = item.image_url || item.img;
            if (imgUrl) {
                let xtra = '';
                if (item.id === 6) xtra = 'transform: translateY(-12px) scale(0.95);';
                return `<img src="${imgUrl}" alt="${item.name}" style="width:${size}px;height:${size}px;object-fit:contain; ${xtra}">`;
            }
            return `<svg viewBox="0 0 24 24" width="${size}" height="${size}" stroke="#9B1A1A" stroke-width="2" fill="none" class="f-icon"><circle cx="12" cy="12" r="10"></circle><path d="M8 12h8"></path></svg>`;
        }


        /* ==================================================
           NAVIGASI
        ================================================== */
        function switchView(name) {
            console.log("Navigating to:", name);
            
            // 1. Sembunyikan SEMUA view
            document.querySelectorAll('.view').forEach(v => {
                v.classList.remove('active');
                v.style.display = 'none';
            });
            
            // 2. Nonaktifkan SEMUA tombol nav
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            
            // 3. Tampilkan View target
            const target = document.getElementById('view-' + name);
            if (target) {
                target.classList.add('active');
                target.style.display = 'flex';
                
                // Urusi header khusus jika ada
                if (name === 'home') document.getElementById('view-home').style.display = 'flex';
            } else {
                // Fallback ke home
                document.getElementById('view-home').classList.add('active');
                document.getElementById('view-home').style.display = 'flex';
            }
            
            // 4. Aktifkan Tombol Nav
            const btn = document.getElementById('nav-' + name);
            if (btn) btn.classList.add('active');

            // 5. Toggle Navbar (Hidden on Cart/Fav as they have their own back buttons)
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                navbar.style.display = (name === 'cart' || name === 'fav') ? 'none' : 'flex';
            }

            // 6. Render Data
            if (name === 'cart') renderCart();
            if (name === 'fav') renderFav();
        }

        function updateBadge() {
            const cnt = cartCount();
            document.querySelectorAll('.badge').forEach(b => {
                b.textContent = cnt;
                b.style.display = cnt > 0 ? 'flex' : 'none';
            });
        }


        /* ==================================================
           CART (Server-Side)
        ================================================== */
        async function loadCart() {
            const res = await apiFetch('/web/cart');
            if (res && res.success) {
                cartItems = res.data;
                updateBadge();
                syncCartToStorage();
                if (document.getElementById('view-cart').classList.contains('active')) renderCart();
            }
        }

        async function addToCart(productId) {
            const res = await apiFetch('/web/cart', 'POST', {
                product_id: productId,
                quantity: 1
            });
            if (res && res.success) {
                await loadCart();
                // Visual feedback: tombol berkedip hijau
                const btns = document.querySelectorAll(`.plus-btn[onclick*="addToCart(${productId})"]`);
                btns.forEach(btn => {
                    const orig = btn.textContent;
                    btn.textContent = '✓';
                    btn.style.background = '#27ae60';
                    setTimeout(() => { btn.textContent = orig; btn.style.background = ''; }, 1000);
                });
            } else if (res) {
                alert(res.message || "Gagal menambah ke keranjang.");
            } else {
                alert("Gagal menambah ke keranjang. Coba login ulang.");
            }
        }

        async function changeQty(id, d) {
            const item = cartItems.find(i => i.product_id == id);
            if (!item) return;

            const newQty = item.quantity + d;
            if (newQty <= 0) {
                await removeCartItem(item.id);
            } else {
                const res = await apiFetch(`/web/cart/${item.id}`, 'PATCH', {
                    quantity: newQty
                });
                if (res && res.success) await loadCart();
            }
        }

        async function removeCartItem(itemId) {
            const res = await apiFetch(`/web/cart/${itemId}`, 'DELETE');
            if (res && res.success) await loadCart();
        }

        async function toggleCheck(itemId) {
            const res = await apiFetch(`/web/cart/${itemId}/toggle-check`, 'PATCH');
            if (res && res.success) await loadCart();
        }

        async function clearCart() {
            // Bulk delete not implemented in backend yet, so we delete each for now
            // or just refresh
            for (const item of cartItems) {
                await apiFetch(`/web/cart/${item.id}`, 'DELETE');
            }
            await loadCart();
        }

        function syncCartToStorage() {
            const hcCart = {};
            cartItems.forEach(item => {
                const p = item.product;
                if (!p) return;
                hcCart[p.id] = {
                    id: p.id, name: p.name, img: p.image_url,
                    basePrice: p.base_price, addChecked: [],
                    addExtra: 0, qty: item.quantity, catatan: item.note || ''
                };
            });
            localStorage.setItem('hc_cart', JSON.stringify(hcCart));
        }

        function updateBadge() {
            const cnt = cartCount();
            const badge = document.getElementById('cart-badge');
            badge.textContent = cnt;
            badge.classList.toggle('show', cnt > 0);
        }

        function renderCart() {
            const inner = document.getElementById('cart-inner');
            const bar = document.getElementById('checkout-bar');

            if (cartItems.length === 0) {
                inner.innerHTML = `
          <div class="empty-state" style="padding: 100px 20px;">
            <div class="e-icon" style="margin-bottom:20px; opacity:0.6;"><svg viewBox="0 0 24 24" width="80" height="80" stroke="#9B1A1A" stroke-width="1.5" fill="none"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></div>
            <h3 style="color:#1A1A1A; margin-bottom:8px;">Keranjang Kosong</h3>
            <p style="color:#888; font-size:0.9rem; margin-bottom:24px;">Kamu belum menambahkan menu apapun ke keranjang.</p>
            <button class="shop-btn" onclick="switchView('home')" style="background:#9B1A1A; color:white; border:none; padding:12px 24px; border-radius:50px; font-weight:700; cursor:pointer; box-shadow:0 4px 12px rgba(155,26,26,0.3);">Mulai Belanja Sekarang</button>
          </div>`;
                if(bar) bar.style.display = 'none';
                return;
            }

            const cnt = cartCount();
            const total = cartTotal();
            document.getElementById('checkout-btn').textContent = `Checkout (${cnt} item) - ${rp(total)}`;
            bar.style.display = 'block';

            const itemsHtml = cartItems.map(item => {
                const prod = item.product;
                if (!prod) return '';
                return `
        <div class="cart-item">
          <div class="chk ${item.is_checked ? 'checked' : ''}" onclick="toggleCheck(${item.id})">
            <svg viewBox="0 0 12 12"><polyline points="2,6 5,9 10,3"/></svg>
          </div>
          <div class="ci-img">${foodHtml(prod, 50)}</div>
          <div class="ci-info">
            <div class="ci-name">${prod.name}</div>
            <div class="ci-price">${rp(prod.base_price)}</div>
          </div>
          <div class="qty-wrap">
            <button class="q-btn" onclick="changeQty(${prod.id}, -1)">−</button>
            <span class="q-num">${item.quantity}</span>
            <button class="q-btn qplus" onclick="changeQty(${prod.id}, 1)">+</button>
          </div>
        </div>`;
            }).join('');

            inner.innerHTML = `
      <div class="sel-row" style="display:flex; align-items:center; gap:10px; padding:10px 16px; background:#FDF8F0; border-bottom:1px solid #EEE;">
        <div class="chk checked"><svg viewBox="0 0 12 12"><polyline points="2,6 5,9 10,3"/></svg></div>
        <span class="sel-lbl" style="font-weight:600; font-size:0.9rem;">Pilih Semua</span>
        <span class="itm-cnt" style="margin-left:auto; color:#888; font-size:0.85rem;">${cnt} item</span>
        <button class="hapus-btn" onclick="clearCart()" style="background:none; border:none; color:#C00; font-size:0.85rem; font-weight:600; cursor:pointer;">Hapus</button>
      </div>
      ${itemsHtml}
      <div class="cart-sum" style="padding:24px 16px; background:white;">
        <div class="sum-row" style="display:flex; justify-content:space-between; margin-bottom:8px; color:#666;">
          <span>Subtotal (${cnt} item)</span>
          <span>${rp(total)}</span>
        </div>
        <div class="tot-row" style="display:flex; justify-content:space-between; font-weight:800; font-size:1.1rem; color:#1A1A1A;">
          <span class="tot-lbl">Total</span>
          <span class="tot-val">${rp(total)}</span>
        </div>
      </div>`;
        }


        /* ==================================================
           FAVORIT (Server-Side)
        ================================================== */
        async function loadFavorites() {
            const res = await apiFetch('/web/favorites');
            if (res && res.success) {
                favoriteIds = new Set(res.data.map(f => f.product_id));
                updateHeartButtons();
                if (document.getElementById('view-fav').classList.contains('active')) renderFav();
            }
        }

        async function toggleFav(id) {
            const res = await apiFetch('/web/favorites/toggle', 'POST', {
                product_id: id
            });
            if (res && res.success) {
                await loadFavorites();
                const btns = document.querySelectorAll(`.heart-btn[data-id="${id}"]`);
                btns.forEach(btn => {
                    btn.classList.add('pop-anim');
                    setTimeout(() => btn.classList.remove('pop-anim'), 300);
                });
            } else if (!res) {
                console.warn("Gagal menyimpan favorit.");
            }
        }

        function updateHeartButtons() {
            document.querySelectorAll(`.heart-btn`).forEach(btn => {
                const id = parseInt(btn.getAttribute('data-id'));
                btn.classList.toggle('liked', favoriteIds.has(id));
            });
        }

        async function removeFav(id) {
            await toggleFav(id);
        }

        function renderFav() {
            const body = document.getElementById('fav-body');
            if (favoriteIds.size === 0) {
                body.innerHTML = `
          <div class="empty-state" style="padding: 100px 20px;">
            <div class="e-icon" style="margin-bottom:20px; opacity:0.6;"><svg viewBox="0 0 24 24" width="80" height="80" stroke="#9B1A1A" stroke-width="1.5" fill="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></div>
            <h3 style="color:#1A1A1A; margin-bottom:8px;">Favorit Kosong</h3>
            <p style="color:#888; font-size:0.9rem; margin-bottom:24px;">Belum ada menu yang kamu tandai sebagai favorit.</p>
            <button class="shop-btn" onclick="switchView('home')" style="background:#9B1A1A; color:white; border:none; padding:12px 24px; border-radius:50px; font-weight:700; cursor:pointer;">Cari Menu Favorit</button>
          </div>`;
                return;
            }

            const favItems = menu.filter(m => favoriteIds.has(m.id));
            body.innerHTML = favItems.map(item => {
                return `
        <div class="fav-item" onclick="window.location.href='{{ url('/produk') }}/${item.id}'">
          <div class="fi-img">${foodHtml({img: item.image_url, name: item.name}, 60)}</div>
          <div class="fi-info">
            <div class="fi-name">${item.name}</div>
            <div class="fi-desc">${item.desc}</div>
            <div class="fi-price">${rp(item.base_price)}</div>
          </div>
          <button class="trash-btn" onclick="event.stopPropagation(); removeFav(${item.id})">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
          </button>
        </div>`;
            }).join('');
        }

        function confirmLogout() {
            openConfirm("Keluar Akun", "Apakah kamu yakin ingin keluar dari akun ini?", doLogout);
        }

        function doLogout() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('logout') }}';
            const csrfAttr = document.createElement('input');
            csrfAttr.type = 'hidden';
            csrfAttr.name = '_token';
            csrfAttr.value = '{{ csrf_token() }}';
            form.appendChild(csrfAttr);
            document.body.appendChild(form);
            form.submit();
        }

        function confirmClearCart() {
            openConfirm("Hapus Keranjang", "Semua item di keranjang akan dihapus. Lanjutkan?", clearCart);
        }

        let confirmCb = null;
        function openConfirm(title, text, cb) {
            document.getElementById('confirm-title').textContent = title;
            document.getElementById('confirm-text').textContent = text;
            confirmCb = cb;
            document.getElementById('ovl-confirm').classList.add('show');
        }
        function closeConfirm() {
            document.getElementById('ovl-confirm').classList.remove('show');
        }
        document.getElementById('confirm-yes-btn').onclick = function () {
            if (confirmCb) confirmCb();
            closeConfirm();
        };

        function claimPromo(mainId, freeId) {
            const promo = { mainId, freeId, name: "Promo Jumat Free Es Teh" };
            // Tambahkan ke koleksi yang sudah diklaim jika belum ada
            let claimed = JSON.parse(localStorage.getItem('hc_claimed_promos') || '[]');
            if (!claimed.find(p => p.mainId === mainId)) {
                claimed.push(promo);
                localStorage.setItem('hc_claimed_promos', JSON.stringify(claimed));
            }
            // Aktifkan langsung
            localStorage.setItem('hc_coupon', JSON.stringify(promo));

            document.getElementById('promo-text').textContent = "Kupon promo berhasil diklaim & terpasang! Kamu akan mendapatkan Es Teh Lemon gratis untuk setiap pembelian Paket Nasi Ayam.";
            document.getElementById('promo-ovl').classList.add('show');
        }

        function applyCoupon(mainId) {
            let claimed = JSON.parse(localStorage.getItem('hc_claimed_promos') || '[]');
            const promo = claimed.find(p => p.mainId === mainId);
            if (promo) {
                localStorage.setItem('hc_coupon', JSON.stringify(promo));
                renderCart();
            }
        }

        function removeCoupon() {
            localStorage.removeItem('hc_coupon');
            renderCart();
        }

        function closePromo(view) {
            document.getElementById('promo-ovl').classList.remove('show');
            if (view === 'cart') switchView('cart');
        }

        let selectedPaymentMethod = 'CASH';

        function selectPayment(method, el) {
            selectedPaymentMethod = method;
            document.querySelectorAll('.pay-opt').forEach(opt => opt.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('qris-info').style.display = (method === 'QRIS_MANUAL' ? 'block' : 'none');
        }

        function handleReceipt(input) {
            if (input.files && input.files[0]) {
                document.getElementById('receipt-preview').innerHTML = `<span style="color:#27ae60">✅ File terpilih: ${input.files[0].name}</span>`;
            }
        }

        function openCheckout() {
            const cnt = cartCount();
            if (cnt === 0) return alert("Keranjang masih kosong!");
            window.location.href = '{{ route('checkout') }}';
        }

        function closeCheckout() {
            document.getElementById('ovl-checkout').classList.remove('show');
        }

        async function doCheckout() {
            // Fungsi ini sekarang hanya membuka modal (diaktifkan tombol checkout lama)
            openCheckout();
        }

        async function confirmCheckout() {
            const address = document.getElementById('chk-address').value;
            if (!address) return alert("Mohon isi alamat pengiriman!");

            const btn = document.getElementById('btn-submit-order');
            btn.textContent = 'Memproses...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('delivery_address', address);
            formData.append('payment_method', selectedPaymentMethod);

            if (selectedPaymentMethod === 'QRIS_MANUAL') {
                const fileInput = document.getElementById('receipt-file');
                if (!fileInput.files[0]) {
                    btn.textContent = 'Pesan Sekarang';
                    btn.disabled = false;
                    return alert("Bukti transfer wajib diunggah untuk metode QRIS!");
                }
                formData.append('payment_receipt', fileInput.files[0]);
            }

            try {
                const response = await fetch('{{ route('order.checkout') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });

                const res = await response.json();
                if (res.success || response.ok) {
                    await clearCart();
                    // Gunakan ID dari respons untuk redirect ke halaman sukses
                    const orderId = res.data ? res.data.id : (res.order ? res.order.id : null);
                    if (orderId) {
                        window.location.href = '{{ route('order.success', ':id') }}'.replace(':id', orderId);
                    } else {
                        window.location.href = '{{ route('order.active') }}';
                    }
                } else {
                    alert("Checkout Gagal: " + (res.message || "Terjadi kesalahan."));
                }
            } catch (err) {
                console.error(err);
                alert("Kesalahan koneksi saat checkout.");
            } finally {
                btn.textContent = 'Pesan Sekarang';
                btn.disabled = false;
            }
        }

        /* ==================================================
           RENDER PRODUK HOME
           ================================================== */
        let isShowingAll = false;
        function toggleSeeAll() {
            isShowingAll = !isShowingAll;
            const btn = document.getElementById('btn-see-all');
            if (btn) btn.textContent = isShowingAll ? 'Sembunyikan' : 'Lihat Semua';
            renderProducts();
        }

        function renderProducts() {
            const grid = document.getElementById('prod-grid');
            if (!grid) return;
            
            try {
                // Safety check: Pastikan menu adalah array
                if (!Array.isArray(menu)) {
                    console.error("Menu data is not an array:", menu);
                    grid.innerHTML = '<p style="text-align:center; padding:20px; color:#999;">Gagal memuat menu.</p>';
                    return;
                }

                // Default ke Set kosong jika favoriteIds bermasalah
                const favs = (favoriteIds instanceof Set) ? favoriteIds : new Set();

                const displayMenu = isShowingAll ? menu : menu.slice(0, 4);

                if (displayMenu.length === 0) {
                    grid.innerHTML = '<p style="text-align:center; padding:20px; color:#999;">Menu sedang tidak tersedia.</p>';
                    return;
                }

                grid.innerHTML = displayMenu.map(item => {
                    const pid = item.id || 0;
                    const pname = item.name || 'Produk';
                    const pdesc = item.description || '';
                    const pprice = item.base_price || 0;

                    return `
      <div class="prod-card">
        <div class="prod-top" onclick="window.location.href='/produk/${pid}'" style="cursor:pointer;">
          <button class="heart-btn ${favs.has(pid) ? 'liked' : ''}" data-id="${pid}"
            onclick="event.stopPropagation(); toggleFav(${pid});">
            <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
          </button>
          <div class="prod-food">${foodHtml(item, 120)}</div>
        </div>
        <div class="prod-bottom">
          <div class="prod-name" onclick="window.location.href='/produk/${pid}'" style="cursor:pointer;">${pname}</div>
          <div class="prod-desc">${pdesc}</div>
          <div class="prod-foot">
            <span class="prod-price">${rp(pprice)}</span>
            <button class="plus-btn" onclick="addToCart(${pid})" style="z-index:10; position:relative;">+</button>
          </div>
        </div>
      </div>`;
                }).join('');
            } catch (err) {
                console.error("Render Error:", err);
                grid.innerHTML = '<p style="text-align:center; padding:20px; color:#999;">Gagal memproses tampilan menu.</p>';
            }
        }

        /* ==================================================
           INIT
        ================================================== */
        function openEditProfile() {
            document.getElementById('ovl-profile').classList.add('show');
        }
        function closeEditProfile() {
            document.getElementById('ovl-profile').classList.remove('show');
        }

        /* ==================================================
           INITIALIZATION
           ================================================== */
        async function initData() {
            try {
                // PRIORITAS 1: Render menu secepat mungkin (pakai data sinkron dari Blade)
                renderProducts();

                // PRIORITAS 2: Load data dinamis (boleh gagal/lambat)
                await Promise.allSettled([
                    loadFavorites(),
                    loadCart()
                ]);

                // Render ulang setelah favorites/cart termuat (untuk update badge/hati)
                renderProducts();

                // Navigasi awal
                const params = new URLSearchParams(window.location.search);
                const initialView = params.get('v') || 'home';
                switchView(initialView);
            } catch (err) {
                console.error("Initialization Error:", err);
                // Fallback minimal
                renderProducts();
            }
        }
        initData();

        // Sync server-side user data to localStorage for the frontend
        @if(session('userName'))
            localStorage.setItem('userName', '{{ session('userName') }}');
        @endif
        @if(session('userEmail'))
            localStorage.setItem('userEmail', '{{ session('userEmail') }}');
        @endif
        @if(session('userRole'))
            localStorage.setItem('userRole', '{{ session('userRole') }}');
        @endif

        // Load user info from Auth if available, fallback to localStorage
        const storedName = "{{ Auth::user()->name ?? '' }}" || localStorage.getItem('userName') || 'Hayo Guest';
        const storedEmail = "{{ Auth::user()->email ?? '' }}" || localStorage.getItem('userEmail') || 'guest@gmail.com';

        document.querySelectorAll('.hdr-name, .prof-name').forEach(el => el.textContent = storedName);
        document.querySelectorAll('.prof-email').forEach(el => el.textContent = storedEmail);

        function doLogout() {
            localStorage.clear();
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }

        // Auto-slide banner
        const bannerScroll = document.getElementById('bannerCarousel');
        if (bannerScroll) {
            let currentBanner = 0;
            const bannerCount = 3;
            setInterval(() => {
                currentBanner = (currentBanner + 1) % bannerCount;
                bannerScroll.scrollTo({
                    left: currentBanner * bannerScroll.clientWidth,
                    behavior: 'smooth'
                });
            }, 4000); // 4 detik geser otomatis
        }

        // Handle URL param to switch view (e.g. ?view=profile)
        const urlParams = new URLSearchParams(window.location.search);
        const viewParam = urlParams.get('view');
        if (viewParam && ['home', 'cart', 'fav', 'profile'].includes(viewParam)) {
            switchView(viewParam);
        }

        // Jalankan Inisialisasi Data
        initData();

        // --- NOTIFICATIONS ---
        async function fetchOrders() {
            try {
                const response = await fetch('{{ route('web.orders.index') }}');
                const res = await response.json();
                if (res.success) {
                    return res.data;
                }
            } catch (err) {
                console.error("Failed to fetch orders:", err);
            }
            return [];
        }

        async function toggleNoti() {
            const ovl = document.getElementById('ovl-noti');
            const isShowing = ovl.classList.contains('show');
            
            if (!isShowing) {
                ovl.classList.add('show');
                const list = document.getElementById('noti-list');
                const orders = await fetchOrders();
                
                // Active statuses: NEW, PENDING_VERIFICATION, PROCESSING, DELIVERING
                const activeOrders = orders.filter(o => !['DONE', 'REJECTED'].includes(o.status));
                
                if (activeOrders.length === 0) {
                    list.innerHTML = '<div class="noti-empty">Tidak ada pesanan aktif</div>';
                } else {
                    list.innerHTML = activeOrders.map(o => {
                        let statusText = o.status;
                        if (o.status === 'NEW') statusText = 'Diproses (Baru)';
                        if (o.status === 'PENDING_VERIFICATION') statusText = 'Menunggu Verifikasi';
                        if (o.status === 'PROCESSING') statusText = 'Sedang Dimasak';
                        if (o.status === 'DELIVERING') statusText = o.payment_method === 'CASH' ? 'Siap Diambil' : 'Dalam Pengiriman';
                        
                        return `
                            <div class="noti-item" onclick="window.location.href='/pesanan/status/${o.id}'">
                                <div class="noti-icon">
                                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2.2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                </div>
                                <div class="noti-info">
                                    <div class="noti-text">Pesanan #${o.order_number}</div>
                                    <div class="noti-status">${statusText}</div>
                                </div>
                            </div>
                        `;
                    }).join('');
                }
            } else {
                ovl.classList.remove('show');
            }
        }

        // Initialize badge on load
        async function initNotiBadge() {
            const orders = await fetchOrders();
            const activeOrders = orders.filter(o => !['DONE', 'REJECTED'].includes(o.status));
            const activeCount = activeOrders.length;
            
            const badge = document.getElementById('bell-badge');
            const bar = document.getElementById('active-order-bar');
            const countText = document.getElementById('aob-count');
            
            if (activeCount > 0) {
                badge.classList.add('show');
                if (bar) {
                    bar.style.display = 'flex';
                    countText.textContent = `${activeCount} Pesanan Aktif`;
                }
            } else {
                badge.classList.remove('show');
                if (bar) bar.style.display = 'none';
            }
        }
        initNotiBadge();

    </script>
    <!-- CHECKOUT OVERLAY -->
    <div class="ovl-checkout" id="ovl-checkout" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:1000; align-items:flex-end; justify-content:center; backdrop-filter:blur(8px);">
        <div class="chk-card">
            <div class="chk-title">Konfirmasi Pesanan</div>
            
            <label class="chk-label">Alamat Pengiriman</label>
            <textarea id="chk-address" class="chk-input" rows="3" placeholder="Jl. Mawar No. 123..."></textarea>
            
            <label class="chk-label">Metode Pembayaran</label>
            <div class="pay-grid" id="pay-grid">
                <div class="pay-opt active" onclick="selectPayment('CASH', this)">
                    <span class="pay-icon">💵</span>
                    <span class="pay-name">Tunai</span>
                </div>
                <div class="pay-opt" onclick="selectPayment('COD', this)">
                    <span class="pay-icon">🛵</span>
                    <span class="pay-name">COD</span>
                </div>
                <div class="pay-opt" onclick="selectPayment('QRIS_MANUAL', this)">
                    <span class="pay-icon">📱</span>
                    <span class="pay-name">QRIS</span>
                </div>
            </div>

            <div id="qris-info" style="display:none;">
                <label class="chk-label">Scan QRIS & Unggah Bukti</label>
                <div class="receipt-sec" onclick="document.getElementById('receipt-file').click()">
                    <div id="receipt-preview" class="receipt-info">
                        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom:8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg><br>
                        Klik untuk upload bukti bayar
                    </div>
                </div>
                <input type="file" id="receipt-file" style="display:none" accept="image/*" onchange="handleReceipt(this)">
            </div>

            <div class="chk-foot">
                <button class="btn-cancel" onclick="closeCheckout()">Batal</button>
                <button class="btn-confirm" id="btn-submit-order" onclick="confirmCheckout()">Pesan Sekarang</button>
            </div>
        </div>
    </div>

</body>

</html>