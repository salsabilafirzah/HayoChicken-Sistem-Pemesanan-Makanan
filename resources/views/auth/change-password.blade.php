<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Ubah Password</title>
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
            padding: 24px 16px 100px;
        }

        .scroll-area::-webkit-scrollbar {
            display: none;
        }

        .field-group {
            margin-bottom: 18px;
        }

        .field-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #555;
            margin-bottom: 8px;
        }

        .field-wrap {
            background: white;
            border-radius: 14px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 2px solid transparent;
            transition: border-color 0.2s;
        }

        .field-wrap:focus-within {
            border-color: #9B1A1A;
        }

        .field-icon {
            flex-shrink: 0;
        }

        .field-icon svg {
            width: 18px;
            height: 18px;
            stroke: #AAA;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .field-input {
            flex: 1;
            padding: 15px 12px;
            font-size: 0.9rem;
            border: none;
            outline: none;
            background: transparent;
            font-family: inherit;
            color: #1A1A1A;
        }

        .toggle-pw {
            cursor: pointer;
            padding: 4px;
            background: none;
            border: none;
        }

        .toggle-pw svg {
            width: 18px;
            height: 18px;
            stroke: #AAA;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .hint {
            font-size: 0.78rem;
            color: #AAA;
            margin-top: 6px;
            padding-left: 4px;
        }

        .strength-wrap {
            margin-top: 8px;
            display: flex;
            gap: 4px;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            border-radius: 99px;
            background: #E8DDD0;
            transition: background 0.3s;
        }

        .strength-bar.weak {
            background: #E03;
        }

        .strength-bar.medium {
            background: #F90;
        }

        .strength-bar.strong {
            background: #090;
        }

        .bottom-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px 20px 24px;
            background: linear-gradient(to top, #F9F4EB 80%, transparent);
        }

        .btn-save {
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

        .btn-save:hover {
            background: #7f1414;
        }

        .btn-save:active {
            transform: scale(0.98);
        }

        .toast {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-60px);
            background: #1A1A1A;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: transform 0.3s;
            white-space: nowrap;
            z-index: 999;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
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
        <div class="toast" id="toast">✅ Password berhasil diubah</div>
        <div class="header">
            <button class="back-btn" onclick="window.location.href='{{ route('home') }}?view=profile'">

                <svg viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
            </button>
            <h1>Ubah Password</h1>
        </div>
        <div class="scroll-area">
            <div class="field-group">
                <div class="field-label">Password Saat Ini</div>
                <div class="field-wrap">
                    <div class="field-icon"><svg viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg></div>
                    <input class="field-input" id="pw-current" type="password" placeholder="Masukkan password lama">
                    <button class="toggle-pw" onclick="toggleVis('pw-current', this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
                <div style="text-align:right; margin-top:8px;">
                    <a href="{{ route('password.reset') }}"
                        style="color:#9B1A1A; font-size:0.8rem; font-weight:700; text-decoration:none;">Lupa
                        Password?</a>
                </div>
            </div>

            <div class="field-group">
                <div class="field-label">Password Baru</div>
                <div class="field-wrap">
                    <div class="field-icon"><svg viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg></div>
                    <input class="field-input" id="pw-new" type="password" placeholder="Minimal 8 karakter"
                        oninput="checkStrength()">
                    <button class="toggle-pw" onclick="toggleVis('pw-new', this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
                <div class="strength-wrap">
                    <div class="strength-bar" id="sb1"></div>
                    <div class="strength-bar" id="sb2"></div>
                    <div class="strength-bar" id="sb3"></div>
                    <div class="strength-bar" id="sb4"></div>
                </div>
                <div class="hint">Gunakan huruf, angka, dan simbol untuk password yang kuat.</div>
            </div>
            <div class="field-group">
                <div class="field-label">Konfirmasi Password Baru</div>
                <div class="field-wrap">
                    <div class="field-icon"><svg viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg></div>
                    <input class="field-input" id="pw-confirm" type="password" placeholder="Ulangi password baru">
                    <button class="toggle-pw" onclick="toggleVis('pw-confirm', this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="bottom-bar">
            <button class="btn-save" onclick="savePassword()">Simpan Password</button>
        </div>
    </div>
    <script>
        function toggleVis(id, btn) {
            const inp = document.getElementById(id);
            inp.type = inp.type === 'password' ? 'text' : 'password';
        }
        function checkStrength() {
            const pw = document.getElementById('pw-new').value;
            let score = 0;
            if (pw.length >= 8) score++;
            if (/[A-Z]/.test(pw)) score++;
            if (/[0-9]/.test(pw)) score++;
            if (/[^A-Za-z0-9]/.test(pw)) score++;
            const cls = score <= 1 ? 'weak' : score <= 2 ? 'medium' : 'strong';
            for (let i = 1; i <= 4; i++) {
                const bar = document.getElementById('sb' + i);
                bar.className = 'strength-bar' + (i <= score ? ' ' + cls : '');
            }
        }
        function savePassword() {
            const c = document.getElementById('pw-current').value;
            const n = document.getElementById('pw-new').value;
            const conf = document.getElementById('pw-confirm').value;
            if (!c || !n || !conf) { alert('Semua field harus diisi!'); return; }
            if (n !== conf) { alert('Konfirmasi password tidak cocok!'); return; }
            if (n.length < 8) { alert('Password minimal 8 karakter!'); return; }
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); window.history.back(); }, 2000);
        }
    </script>
</body>

</html>