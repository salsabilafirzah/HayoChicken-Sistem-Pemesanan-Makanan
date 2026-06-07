<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayo Chicken - Tambah Alamat</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body {
            background: #9B1A1A;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh; min-height: 100dvh;
            display: flex; justify-content: center; align-items: center;
            overflow: hidden;
        }
        .app {
            width: 100%; max-width: 420px;
            height: 100vh; height: 100dvh;
            background: #F9F4EB;
            display: flex; flex-direction: column;
            overflow: hidden; position: relative;
        }
        .header {
            background: #9B1A1A;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            padding: 52px 20px 22px;
            display: flex; align-items: center; gap: 14px;
            flex-shrink: 0;
        }
        .back-btn {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.22);
            border: none; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; flex-shrink: 0;
        }
        .back-btn svg { width: 20px; height: 20px; stroke: white; fill: none; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
        .header h1 { color: white; font-size: 1.2rem; font-weight: 700; }
        .scroll-area {
            flex: 1; overflow-y: auto; overflow-x: hidden;
            padding: 16px 16px 100px;
        }
        .scroll-area::-webkit-scrollbar { display: none; }

        .form-card {
            background: white; border-radius: 16px;
            padding: 16px; margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .form-label { font-size: 0.85rem; font-weight: 600; color: #555; margin-bottom: 8px; display: block; }
        .field-input {
            width: 100%; padding: 12px 14px;
            background: #F5EFE6; border: none; border-radius: 10px;
            font-family: inherit; font-size: 0.88rem; color: #333;
            outline: none; margin-bottom: 16px;
        }
        .field-input::placeholder { color: #BBAA99; }
        .field-input:focus { background: #EDE4D6; }

        .bottom-bar {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 16px 20px 24px;
            background: linear-gradient(to top, #F9F4EB 80%, transparent);
        }
        .btn-save {
            width: 100%; background: #9B1A1A; color: white;
            border: none; border-radius: 50px;
            padding: 16px; font-size: 1rem; font-weight: 700;
            cursor: pointer; font-family: inherit;
        }

        @media (min-width: 480px) {
            body { background: radial-gradient(circle, #b81419 0%, #680507 100%); }
            .app { height: 850px; border-radius: 40px; border: 8px solid rgba(255,255,255,0.1); box-shadow: 0 20px 50px rgba(0,0,0,0.4); }
        }
    </style>
</head>
<body>
<div class="app">
    <div class="header">
        <button class="back-btn" onclick="window.history.back()">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <h1>Tambah Alamat</h1>
    </div>
    <div class="scroll-area">
        <div class="form-card">
            <label class="form-label">Label Alamat (misal: Rumah, Kantor)</label>
            <input class="field-input" type="text" placeholder="Masukkan label alamat">
            
            <label class="form-label">Nama Penerima</label>
            <input class="field-input" type="text" placeholder="Masukkan nama penerima">

            <label class="form-label">Nomor Telepon</label>
            <input class="field-input" type="text" placeholder="Masukkan nomor telepon">
            
            <label class="form-label">Alamat Lengkap</label>
            <textarea class="field-input" rows="3" placeholder="Masukkan alamat lengkap" style="resize:none"></textarea>
        </div>
    </div>
    <div class="bottom-bar">
        <button class="btn-save" onclick="saveAddress()">Simpan Alamat</button>
    </div>
</div>
<script>
function saveAddress() {
    alert('Alamat berhasil ditambahkan!');
    window.location.href = '{{ route('address.saved') }}';
}
</script>
</body>
</html>
