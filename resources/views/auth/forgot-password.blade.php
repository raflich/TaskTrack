<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Forgot Password - TaskTrack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>*, *::before, *::after { font-family: 'Poppins', sans-serif !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#fdf6ee] flex flex-col items-center justify-center p-4">

    <!-- Header / Logo -->
    <div class="mb-6 text-center">
        <div class="flex justify-center mb-3">
            <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/25">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-[#4a270f]">TaskTrack</h1>
        <p class="text-xs text-gray-500 mt-1.5 max-w-[280px] mx-auto leading-relaxed">
            Your streamlined workspace for personal momentum and professional accomplishment.
        </p>
    </div>

    <!-- Card Container -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl shadow-[#4a270f]/5 border border-[#faf6f0] p-8">

        <h2 class="text-xl font-bold text-[#4a270f] mb-2">Forgot Password?</h2>
        <p class="text-xs text-gray-400 mb-6 leading-relaxed">
            No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </p>

        <!-- Session Status -->
        @if(session('status'))
            <div class="mb-4 px-4 py-2.5 bg-green-50 border border-green-200 text-green-700 rounded-xl text-xs">
                {{ session('status') }}
            </div>
        @endif

        <!-- Errors -->
        @if($errors->any())
            <div class="mb-4 px-4 py-2.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1.5">
                    Email Address
                </label>
                <div class="flex items-center bg-[#faf5f0] border border-[#eedecc] rounded-xl px-4 focus-within:ring-2 focus-within:ring-orange-400 focus-within:border-transparent transition-all duration-300">
                    <span class="text-gray-400 mr-3 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#bfa38a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="Cihuyyy@example.com"
                           class="bg-transparent py-3 w-full text-sm text-gray-800 placeholder-gray-300"
                           style="border: none !important; outline: none !important; box-shadow: none !important;"
                           required autofocus />
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold
                           py-3 rounded-xl text-sm transition-all duration-300 shadow-lg shadow-orange-500/20 active:scale-[0.99]">
                Send Email
            </button>

            <!-- Back to Login Link -->
            <div class="text-center mt-6">
                <a href="{{ route('login') }}"
                   class="text-xs font-semibold text-gray-400 hover:text-orange-500 transition-colors duration-200">
                    Back to Login
                </a>
            </div>

        </form>
    </div>

</body>
</html>
