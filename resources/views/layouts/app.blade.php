<!DOCTYPE html>
<html lang="id" class="h-screen overflow-hidden">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>TaskTrack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fdf6ee] text-[#4a270f] antialiased h-screen overflow-hidden flex flex-col" style="font-family: 'Plus Jakarta Sans', sans-serif;">

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

</body>
</html>