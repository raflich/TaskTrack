<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - TaskTrack</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#fdf6ee] flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm p-8">

        {{-- Logo --}}
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-orange-500">TaskTrack</h1>
            <p class="text-sm text-gray-400 mt-1">Masuk ke akun kamu</p>
        </div>

        {{-- Session Status --}}
        @if(session('status'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Errors --}}
        @if($errors->any())
            <div class="mb-4 px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" onsubmit="sessionStorage.setItem('tab_session_active', 'true');">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="email@example.com"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-orange-400"
                       required autofocus/>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Password
                </label>
                <input type="password" name="password"
                       placeholder="••••••••"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-orange-400"
                       required/>
            </div>

            {{-- Remember Me --}}
            <div class="mb-6 flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer">
                    <input type="checkbox" name="remember" value="1"
                           class="rounded border-gray-300 text-orange-500 focus:ring-orange-400"
                           {{ old('remember') ? 'checked' : '' }} />
                    Remember me
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-sm text-orange-500 hover:underline">
                        Lupa password?
                    </a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold
                           py-2 rounded-lg text-sm transition">
                Log In
            </button>

            {{-- Link ke Register --}}
            <p class="text-center text-sm text-gray-400 mt-4">
                Belum punya akun?
                <a href="{{ route('register') }}"
                   class="text-orange-500 font-semibold hover:underline">
                    Register
                </a>
            </p>

        </form>
    </div>

</body>
</html>