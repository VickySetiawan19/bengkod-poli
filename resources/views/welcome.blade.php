<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poliklinik</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@5/daisyui.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 text-slate-800">

    <div class="max-w-lg w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">
        <!-- Icon / Badge -->
        <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl border border-teal-100">
            <i class="fa-regular fa-clock"></i>
        </div>

        <!-- Section Selamat Datang -->
        <div class="mb-6">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">
                Selamat Datang
            </h2>
            <p class="text-sm text-slate-600 mt-2">
                {{ now()->translatedFormat('l, d F Y') }} - Berikut ringkasan aktivitas anda hari ini
            </p>
        </div>

        <!-- Real-Time Clock Box -->
        <div class="bg-slate-900 text-white rounded-xl p-6 shadow-inner my-6">
            <p class="text-xs uppercase tracking-widest text-slate-400 font-semibold mb-1">
                Waktu Real-Time
            </p>
            <div id="live-clock" class="text-4xl sm:text-5xl font-extrabold tracking-wider font-mono text-teal-400">
                {{ now()->format('H:i:s') }}
            </div>
            <p id="live-date" class="text-xs text-slate-300 mt-2">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        <!-- Simple Status Info -->
        <div class="flex items-center justify-center gap-2 text-xs text-slate-500">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Tailwind CSS & Laravel Berjalan Normal</span>
        </div>
    </div>

    <!-- Script Real-Time Clock -->
    <script>
        function updateClock() {
            const now = new Date();

            // Jam:Menit:Detik
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const clockEl = document.getElementById('live-clock');
            if (clockEl) {
                clockEl.textContent = `${hours}:${minutes}:${seconds}`;
            }

            // Hari dan Tanggal
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            const dateStr = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
            const dateEl = document.getElementById('live-date');
            if (dateEl) {
                dateEl.textContent = dateStr;
            }
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
