<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Masuk</title>
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
            background-color: rgba(255,255,255,0.25);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .back-btn:hover { background-color: rgba(255,255,255,0.35); }

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

        .field-group { margin-bottom: 20px; }

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

        .field-group input::placeholder { color: #A89A8E; }
        .field-group input:focus { background-color: #D0C4B7; }

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
            margin-top: 16px;
            margin-bottom: 22px;
            transition: background-color 0.2s, transform 0.1s;
        }

        .btn-submit:hover { background-color: #7f1414; }
        .btn-submit:active { transform: scale(0.98); }

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

        /* Error msg */
        .err-msg {
            background: rgba(155,26,26,0.1);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.85rem;
            color: #9B1A1A;
            font-weight: 600;
            margin-bottom: 14px;
            display: none;
        }

        @media (min-width: 480px) {
            body { background: radial-gradient(circle, #b81419 0%, #680507 100%); }
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
            <button class="back-btn" onclick="window.location.href='{{ route('welcome') }}'">
                <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <h1>Masuk Akun</h1>
            <p>Selamat datang kembali!</p>
        </div>

        <form class="form-area" action="{{ route('login.post') }}" method="POST">
            @csrf
            
            @if($errors->any())
            <div class="err-msg" style="display:block;">{{ $errors->first() }}</div>
            @endif

            <div class="field-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@gmail.com" required>
            </div>

            <div class="field-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Min 8 karakter" required>
                <div style="text-align:right; margin-top:6px;">
                    <a href="{{ route('password.reset') }}" style="color:#9B1A1A; font-size:0.82rem; font-weight:600; text-decoration:none;">Lupa Password?</a>
                </div>
            </div>

            <button class="btn-submit" type="submit">Masuk Sekarang</button>

            <p class="footer-text">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
        </form>
    </div>

    <script>
        // No client-side doLogin needed anymore
    </script>
</body>
</html>
