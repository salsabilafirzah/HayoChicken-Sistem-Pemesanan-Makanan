<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hayo Chicken - Nikmati kelezatan ayam goreng krispi dengan rasa pedas yang bikin nagih! Masuk atau daftar sekarang.">
    <meta name="theme-color" content="#9e090f">
    <title>Hayo Chicken</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <main class="login-container">
        <!-- Logo Area -->
        <div class="logo-area">
            <img src="{{ asset('logo_hayo.png') }}" alt="Hayo Chicken Logo" class="logo-image">
        </div>

        <!-- Buttons Area -->
        <div class="button-area">
            <button class="btn-outline" onclick="window.location.href='{{ route('register') }}'">Daftar</button>
            <button class="btn-outline" onclick="window.location.href='{{ route('login') }}'">Masuk</button>
        </div>
    </main>
</body>
</html>
