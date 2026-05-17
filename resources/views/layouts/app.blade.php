<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>TaskTrack</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#1a1a1a] text-white min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="flex items-center justify-between px-6 py-3 bg-[#1a1a1a] border-b border-gray-700">
        <span class="text-orange-400 font-bold text-xl">TaskTrack</span>
        <div class="flex items-center gap-3">
            <button class="text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
            </button>
            <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center
                        text-xs font-bold uppercase text-white">
                {{ Auth::user()->avatar }}
            </div>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-44 bg-[#1a1a1a] border-r border-gray-700 flex flex-col justify-between py-4 shrink-0">
            <div class="flex flex-col gap-1 px-3">

                <a href="{{ route('kanban.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs('kanban.index')
                             ? 'bg-orange-500 text-white'
                             : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2
                                 a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2
                                 a2 2 0 012 2m0 0v10"/>
                    </svg>
                    Kanban Board
                </a>

                <a href="{{ route('listview.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('listview.index')
                            ? 'bg-orange-500 text-white'
                            : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    List View
                </a>

                
                <a href="{{ route('trash.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs('trash.index')
                             ? 'bg-orange-500 text-white'
                             : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                                 L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Trash
                </a>

            </div>

            {{-- Logout --}}
            <div class="px-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 px-3 py-2 w-full rounded-lg text-sm
                                   text-gray-400 hover:text-white hover:bg-gray-800 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3
                                     3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 overflow-auto bg-[#fdf6ee]">
            @yield('content')
        </main>

    </div>

</body>
</html>