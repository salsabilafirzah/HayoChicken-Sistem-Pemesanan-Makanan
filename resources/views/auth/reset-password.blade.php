<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: #9e090f;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .page-container {
            width: 100%;
            max-width: 420px;
            height: 100vh;
            height: 100dvh;
            background-color: #EDE0D0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .auth-header {
            background-color: #9B1A1A;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            padding: 52px 24px 36px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            text-align: center;
            flex-shrink: 0;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.25);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .back-btn:hover {
            background-color: rgba(255, 255, 255, 0.35);
        }

        .back-btn svg {
            width: 20px;
            height: 20px;
            stroke: white;
            stroke-width: 2.5;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .auth-header h1 {
            color: #ffffff;
            font-size: 1.55rem;
            font-weight: 700;
            margin-top: 14px;
            margin-bottom: 6px;
        }

        .auth-header p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.9rem;
            font-weight: 400;
        }

        .form-area {
            padding: 36px 24px 32px 24px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .field-group {
            margin-bottom: 20px;
        }

        .field-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: #333333;
            margin-bottom: 8px;
        }

        .field-group input {
            width: 100%;
            padding: 14px 16px;
            background-color: #DDD3C6;
            border: none;
            border-radius: 10px;
            font-size: 0.93rem;
            font-family: 'Inter', sans-serif;
            color: #333;
            outline: none;
            transition: background-color 0.2s;
        }

        .field-group input::placeholder {
            color: #A89A8E;
        }

        .field-group input:focus {
            background-color: #D0C4B7;
        }

        .field-group input.error {
            border: 1.5px solid #9B1A1A;
        }

        .info-box {
            background: rgba(155, 26, 26, 0.08);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 0.83rem;
            color: #7A3A3A;
            line-height: 1.55;
            margin-bottom: 20px;
        }

        .btn-submit {
            width: 100%;
            background-color: #9B1A1A;
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 15px;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            margin-top: 8px;
            margin-bottom: 22px;
            transition: background-color 0.2s, transform 0.1s;
        }

        .btn-submit:hover {
            background-color: #7f1414;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .footer-text {
            text-align: center;
            font-size: 0.87rem;
            color: #7A6A60;
        }

        .footer-text a {
            color: #9B1A1A;
            font-weight: 700;
            text-decoration: none;
        }

        .success-box {
            background: rgba(46, 204, 113, 0.1);
            border: 1.5px solid #2ECC71;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 0.85rem;
            color: #1A7A4A;
            font-weight: 600;
            text-align: center;
            display: none;
            margin-bottom: 20px;
        }

        .err-msg {
            background: rgba(155, 26, 26, 0.1);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.85rem;
            color: #9B1A1A;
            font-weight: 600;
            margin-bottom: 14px;
            display: none;
        }

        @media (min-width: 480px) {
            body {
                background: radial-gradient(circle, #b81419 0%, #680507 100%);
            }

            .page-container {
                height: 850px;
                border-radius: 40px;
                border: 8px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
                overflow: hidden;
            }
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="auth-header">
            <button class="back-btn" onclick="window.location.href='{{ route('login') }}'">
                <svg viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
            </button>
            <h1>Lupa Password</h1>
            <p>Masukkan emailmu untuk mereset password</p>
        </div>

        <div class="form-area">
            <div class="info-box" style="display:flex; align-items:flex-start; gap:10px;">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2"
                    style="margin-top:2px; flex-shrink:0;">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
                <span>Kami akan mengirimkan tautan reset password ke alamat email kamu. Tautan berlaku selama <strong>15
                        menit</strong>.</span>
            </div>


            <div class="success-box" id="success-box"
                style="display:none; align-items:flex-start; gap:10px; text-align:left;">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="#1A7A4A" fill="none" stroke-width="2.5"
                    style="margin-top:2px; flex-shrink:0;">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <span>Tautan reset password telah dikirim ke email kamu. Silakan cek inbox atau folder spam.</span>
            </div>


            <div class="err-msg" id="err-msg">Email tidak ditemukan dalam sistem.</div>

            <div class="field-group" id="email-group">
                <label>Email</label>
                <input type="email" id="email-input" placeholder="email@gmail.com">
            </div>

            <button class="btn-submit" id="btn-kirim" type="button" onclick="kirimReset()">Kirim Tautan Reset</button>

            <p class="footer-text">Ingat password? <a href="{{ route('login') }}">Masuk</a></p>
        </div>
    </div>

    <script>
        function kirimReset() {
            const email = document.getElementById('email-input').value.trim();
            const errEl = document.getElementById('err-msg');
            const inp = document.getElementById('email-input');
            const successBox = document.getElementById('success-box');

            if (!email || !email.includes('@')) {
                errEl.textContent = 'Masukkan alamat email yang valid.';
                errEl.style.display = 'block';
                inp.classList.add('error');
                return;
            }

            errEl.style.display = 'none';
            inp.classList.remove('error');
            successBox.style.display = 'flex';


            // Sembunyikan form setelah berhasil
            document.getElementById('email-group').style.display = 'none';
            document.getElementById('btn-kirim').style.display = 'none';
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') kirimReset();
        });
    </script>
</body>

</html>