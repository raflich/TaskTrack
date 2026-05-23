<aside class="w-64 bg-[#fdf6ee] border-r-[3px] border-[#eae0d5] flex flex-col justify-between py-6 px-4 shrink-0 h-full z-40">
    <div class="flex flex-col gap-2">
        <!-- Kanban Board Option -->
        <a href="{{ route('kanban.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200
                  {{ request()->routeIs('kanban.index')
                     ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25'
                     : 'text-[#8c7462] hover:text-orange-500 hover:bg-[#faf2e9]' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10" />
            </svg>
            Kanban Board
        </a>

        <!-- List View Option -->
        <a href="{{ route('listview.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200
                  {{ request()->routeIs('listview.index')
                     ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25'
                     : 'text-[#8c7462] hover:text-orange-500 hover:bg-[#faf2e9]' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            List View
        </a>

        <!-- Trash Option -->
        <a href="{{ route('trash.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200
                  {{ request()->routeIs('trash.index')
                     ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25'
                     : 'text-[#8c7462] hover:text-orange-500 hover:bg-[#faf2e9]' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Trash
        </a>
    </div>

    {{-- Logout --}}
    <div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-3 px-4 py-3 w-full rounded-xl text-sm font-semibold text-[#8c7462] hover:text-red-500 hover:bg-red-50 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
