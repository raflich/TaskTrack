<nav class="flex items-center justify-between bg-[#fdf6ee] border-b border-[#eae0d5] shrink-0 h-[73px] z-50"
     style="display: flex; align-items: center; justify-content: space-between; padding-left: 64px; padding-right: 64px;">

    <!-- Brand Title -->
    <a href="{{ route('kanban.index') }}" class="hover:opacity-85 transition-opacity"
       style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 32px; font-weight: 800; color: #7c4a24; letter-spacing: -0.025em; text-decoration: none;">
        TaskTrack
    </a>

    <div class="flex items-center" style="display: flex; align-items: center; gap: 20px;">

        <!-- Search: icon + collapsible input -->
        <div class="relative flex items-center" id="searchWrapper" style="position: relative; display: flex; align-items: center;">

            <!-- Magnifying glass button -->
            <button type="button" id="searchBtn"
                    style="background: none; border: none; cursor: pointer; padding: 6px; display: flex; align-items: center; justify-content: center; color: #8c7462; border-radius: 9999px; transition: color 0.2s, background 0.2s;"
                    onmouseover="this.style.color='#f97316'; this.style.background='#faf2e9'"
                    onmouseout="this.style.color='#8c7462'; this.style.background='none'">
                <svg width="21" height="21" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            <!-- Collapsible input -->
            <input type="text" id="globalSearch"
                   placeholder="Search tasks..."
                   style="width: 0; opacity: 0; padding: 6px 0; border: none; outline: none; box-shadow: none;
                          background: #faf5f0; border-radius: 9999px;
                          font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #4a270f;
                          transition: width 0.3s ease, opacity 0.3s ease, padding 0.3s ease;
                          margin-left: 4px; overflow: hidden;"/>

            <!-- No result hint -->
            <div id="searchNoHint" class="hidden"
                 style="position: absolute; right: 0; top: calc(100% + 8px);
                        background: #fff; border: 1px solid #f0e6d3; border-radius: 12px;
                        padding: 10px 16px; font-size: 12px; color: #bfa38a;
                        font-family: 'Plus Jakarta Sans', sans-serif; white-space: nowrap;
                        box-shadow: 0 4px 16px rgba(124,74,36,0.08); z-index: 50;">
                Tidak ada task yang cocok.
            </div>
        </div>

        <!-- Avatar -->
        <div style="width: 36px; height: 36px; border-radius: 9999px; background: #dbeafe;
                    color: #1e40af; display: flex; align-items: center; justify-content: center;
                    font-size: 12px; font-weight: 700; text-transform: uppercase;
                    border: 1px solid #b9d5fd; font-family: 'Plus Jakarta Sans', sans-serif; flex-shrink: 0;">
            {{ Auth::user()->avatar }}
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchBtn = document.getElementById('searchBtn');
    const globalSearch = document.getElementById('globalSearch');

    // Expand search on button click
    searchBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        globalSearch.style.width   = '220px';
        globalSearch.style.opacity = '1';
        globalSearch.style.padding = '6px 16px';
        globalSearch.focus();
    });

    // Collapse search on blur if empty
    globalSearch.addEventListener('blur', function () {
        if (this.value.trim() === '') {
            collapseSearch();
        }
    });

    // Collapse on click outside
    document.addEventListener('click', function (e) {
        if (!document.getElementById('searchWrapper').contains(e.target)) {
            if (globalSearch.value.trim() === '') {
                collapseSearch();
            }
            // Reset all filters when clicking outside with empty search
            if (globalSearch.value.trim() === '') {
                resetFilters();
            }
        }
    });

    function collapseSearch() {
        globalSearch.style.width   = '0';
        globalSearch.style.opacity = '0';
        globalSearch.style.padding = '6px 0';
    }

    function resetFilters() {
        document.querySelectorAll('[data-task-id]').forEach(c => c.style.display = '');
        document.querySelectorAll('.task-row').forEach(r => r.style.display = '');
        document.querySelectorAll('.trash-item').forEach(i => i.style.display = '');
        ['TODO','DOING','DONE'].forEach(status => {
            const col   = document.getElementById(`col-${status}`);
            const badge = document.getElementById(`count-${status}`);
            if (col && badge) badge.textContent = col.querySelectorAll('[data-task-id]').length;
        });
        const noResultRow = document.getElementById('noResultRow');
        const tableFooter = document.getElementById('tableFooter');
        if (noResultRow) noResultRow.classList.add('hidden');
        if (tableFooter) tableFooter.classList.remove('hidden');
        const hint = document.getElementById('searchNoHint');
        if (hint) hint.classList.add('hidden');
    }
});
</script>
