<!DOCTYPE html>
<html lang="id" class="h-screen overflow-hidden">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>TaskTrack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fdf6ee] text-[#4a270f] antialiased h-screen overflow-hidden flex flex-col" style="font-family: 'Poppins', sans-serif;">

    {{-- NAVBAR --}}
    @include('layouts.partials.navbar')

    <div class="flex flex-row flex-1 overflow-hidden h-[calc(100vh-73px)]">

        {{-- SIDEBAR --}}
        @include('layouts.partials.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1 bg-[#fffbf7] p-8" style="overflow-y: auto; overflow-x: hidden;">
            @yield('content')
        </main>

    </div>

    {{-- GLOBAL LIVE SEARCH SCRIPT --}}
    <script>
    const globalSearch = document.getElementById('globalSearch');
    const searchNoHint = document.getElementById('searchNoHint');

    globalSearch.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();
        let totalVisible = 0;

        // ── Kanban Board: filter task cards ──────────────────────────
        const kanbanCards = document.querySelectorAll('[data-task-id]');
        kanbanCards.forEach(card => {
            const titleEl = card.querySelector('.task-title');
            const title   = titleEl ? titleEl.textContent.toLowerCase() : '';
            const show    = !keyword || title.includes(keyword);
            card.style.display = show ? '' : 'none';
            if (show) totalVisible++;
        });
        // Update column count badges
        ['TODO', 'DOING', 'DONE'].forEach(status => {
            const col   = document.getElementById(`col-${status}`);
            const badge = document.getElementById(`count-${status}`);
            if (col && badge) {
                const visibleCount = col.querySelectorAll('[data-task-id]').length
                                   - col.querySelectorAll('[data-task-id][style*="none"]').length;
                badge.textContent = visibleCount;
            }
        });

        // ── List View: filter table rows ──────────────────────────────
        const taskRows = document.querySelectorAll('.task-row');
        taskRows.forEach(row => {
            const titleEl = row.querySelector('.task-title');
            const title   = titleEl ? titleEl.textContent.toLowerCase() : '';
            const show    = !keyword || title.includes(keyword);
            row.style.display = show ? '' : 'none';
            if (show) totalVisible++;
        });
        const noResultRow = document.getElementById('noResultRow');
        const tableFooter = document.getElementById('tableFooter');
        if (noResultRow) {
            const anyRowVisible = [...taskRows].some(r => r.style.display !== 'none');
            noResultRow.classList.toggle('hidden', anyRowVisible || !keyword);
        }
        if (tableFooter) tableFooter.classList.toggle('hidden', keyword.length > 0);

        // ── Trash: filter trash items ─────────────────────────────────
        const trashItems = document.querySelectorAll('.trash-item');
        trashItems.forEach(item => {
            const titleEl = item.querySelector('.trash-title');
            const title   = titleEl ? titleEl.textContent.toLowerCase() : '';
            const show    = !keyword || title.includes(keyword);
            item.style.display = show ? '' : 'none';
            if (show) totalVisible++;
        });

        // ── No result hint under search bar ──────────────────────────
        const hasElements = kanbanCards.length + taskRows.length + trashItems.length > 0;
        if (searchNoHint) {
            searchNoHint.classList.toggle('hidden', !keyword || totalVisible > 0 || !hasElements);
        }
    });

    // Reset filters when input is cleared
    globalSearch.addEventListener('input', function () {
        if (this.value.trim() === '') {
            document.querySelectorAll('[data-task-id]').forEach(c => c.style.display = '');
            document.querySelectorAll('.task-row').forEach(r => r.style.display = '');
            document.querySelectorAll('.trash-item').forEach(i => i.style.display = '');
            ['TODO', 'DOING', 'DONE'].forEach(status => {
                const col   = document.getElementById(`col-${status}`);
                const badge = document.getElementById(`count-${status}`);
                if (col && badge) badge.textContent = col.querySelectorAll('[data-task-id]').length;
            });
            const noResultRow = document.getElementById('noResultRow');
            const tableFooter = document.getElementById('tableFooter');
            if (noResultRow) noResultRow.classList.add('hidden');
            if (tableFooter) tableFooter.classList.remove('hidden');
            if (searchNoHint) searchNoHint.classList.add('hidden');
        }
    });
    </script>

    {{-- MODAL LOGOUT CONFIRMATION --}}
    <div id="modalLogout" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center px-4 transition-opacity duration-200" style="z-index: 9999;">
        <div id="logoutCard" class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-xl text-center relative transition-all duration-200 transform scale-95 opacity-0" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
            
            <!-- Circle Exit Icon -->
            <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
            </div>

            <h2 style="font-size: 18px; font-weight: 700; color: #2d1e17; margin: 0 0 8px; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">Logout Account?</h2>
            
            <p style="font-size: 14px; color: #8c7462; line-height: 1.5; margin: 0 0 24px; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                Are you sure you want to log out?<br>You'll need to sign in again to continue.
            </p>

            <!-- Form and Action Buttons -->
            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                @csrf
                <button type="submit" 
                        style="width: 100%; padding: 10px 0; background-color: #f97316; color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 12px; border: none; cursor: pointer; transition: background-color 0.2s; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; margin-bottom: 12px;"
                        onmouseover="this.style.backgroundColor='#ea580c'"
                        onmouseout="this.style.backgroundColor='#f97316'">
                    Logout
                </button>
            </form>
            
            <button onclick="closeLogoutModal()" 
                    style="background: none; border: none; color: #8c7462; font-size: 14px; font-weight: 500; cursor: pointer; transition: color 0.2s; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;"
                    onmouseover="this.style.color='#5a4a3a'"
                    onmouseout="this.style.color='#8c7462'">
                Cancel
            </button>
        </div>
    </div>

    <script>
    function openLogoutModal() {
        const modal = document.getElementById('modalLogout');
        const card = document.getElementById('logoutCard');
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeLogoutModal() {
        const modal = document.getElementById('modalLogout');
        const card = document.getElementById('logoutCard');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    document.getElementById('modalLogout').addEventListener('click', function(e) {
        if (e.target === this) closeLogoutModal();
    });
    </script>

    {{-- Force logout jika browser/tab ditutup dan login tanpa remember me --}}
    @if(!session('remember_me'))
        <form id="forced-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
        <script>
            if (!sessionStorage.getItem('tab_session_active')) {
                document.getElementById('forced-logout-form').submit();
            }
        </script>
    @endif

</body>
</html>