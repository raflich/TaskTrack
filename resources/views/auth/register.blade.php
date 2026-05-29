<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Register - TaskTrack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>*, *::before, *::after { font-family: 'Poppins', sans-serif !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#fdf6ee] flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm p-8">

        {{-- Logo --}}
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-orange-500">TaskTrack</h1>
            <p class="text-sm text-gray-400 mt-1">Buat akun baru</p>
        </div>

        {{-- Errors --}}
        @if($errors->any())
            <div class="mb-4 px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" onsubmit="sessionStorage.setItem('tab_session_active', 'true');">
            @csrf

            {{-- Nama --}}
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Nama Lengkap
                </label>
                <input type="text" name="nama_user" value="{{ old('nama_user') }}"
                       placeholder="cihuyy"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-orange-400"
                       required autofocus/>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="email@example.com"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-orange-400"
                       required/>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Password
                </label>
                <input type="password" name="password"
                       placeholder="Minimal 8 karakter"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-orange-400"
                       required/>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-6">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation"
                       placeholder="Ulangi password"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-orange-400"
                       required/>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold
                           py-2 rounded-lg text-sm transition">
                Register
            </button>

            {{-- Link ke Login --}}
            <p class="text-center text-sm text-gray-400 mt-4">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                   class="text-orange-500 font-semibold hover:underline">
                    Login
                </a>
            </p>

        </form>
    </div>

</body>
</html>