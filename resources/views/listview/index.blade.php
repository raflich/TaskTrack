@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <h1 class="text-xl font-bold text-gray-800 mb-1">List View</h1>
    <p class="text-sm text-gray-400 mb-6">Manage your personal productivity across all workflows.</p>

    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search + Filter + Sort --}}
    <div class="flex gap-3 mb-4 flex-wrap">

        {{-- Search (live, tidak perlu submit) --}}
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input type="text" id="searchInput"
                   placeholder="Search task title..."
                   class="pl-9 pr-4 py-1.5 border border-gray-200 rounded-lg text-sm
                          text-gray-800 bg-white placeholder-gray-400
                          focus:outline-none focus:ring-2 focus:ring-orange-400 w-56"/>
        </div>

        {{-- Filter & Sort --}}
        <form method="GET" action="{{ route('listview.index') }}" class="flex gap-3">
            <select name="status" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-700
                           bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 cursor-pointer">
                <option value="all"   {{ !request('status') || request('status') === 'all' ? 'selected' : '' }}>Filter Status</option>
                <option value="TODO"  {{ request('status') === 'TODO'  ? 'selected' : '' }}>To Do</option>
                <option value="DOING" {{ request('status') === 'DOING' ? 'selected' : '' }}>Doing</option>
                <option value="DONE"  {{ request('status') === 'DONE'  ? 'selected' : '' }}>Done</option>
            </select>

            <select name="sort" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-700
                           bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 cursor-pointer">
                <option value="due_date" {{ !request('sort') || request('sort') === 'due_date' ? 'selected' : '' }}>Sort: Due Date</option>
                <option value="priority" {{ request('sort') === 'priority' ? 'selected' : '' }}>Sort: Priority</option>
                <option value="title"    {{ request('sort') === 'title'    ? 'selected' : '' }}>Sort: Title</option>
            </select>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-gray-500 font-semibold">Task Title</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-semibold">Due Date</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-semibold">Priority</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-semibold">Status</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-semibold">Details</th>
                </tr>
            </thead>
            <tbody id="taskTableBody" class="divide-y divide-gray-50">
                @forelse($tasks as $task)
                    <tr class="task-row hover:bg-gray-50 transition
                               {{ $task->status_task === 'DONE' ? 'opacity-50' : '' }}">

                        {{-- Task Title --}}
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-1 h-8 rounded-full shrink-0
                                            {{ $task->prioritas === 'HIGH'
                                               ? 'bg-red-500'
                                               : ($task->prioritas === 'MEDIUM' ? 'bg-yellow-500' : 'bg-blue-400') }}">
                                </div>
                                <span class="task-title font-medium text-gray-800
                                             {{ $task->status_task === 'DONE' ? 'line-through text-gray-400' : '' }}">
                                    {{ $task->judul_task }}
                                </span>
                            </div>
                        </td>

                        {{-- Due Date --}}
                        <td class="px-5 py-3 text-gray-500">
                            {{ $task->tanggal_deadline ? $task->tanggal_deadline->format('M d, Y') : '-' }}
                        </td>

                        {{-- Priority --}}
                        <td class="px-5 py-3">
                            <span class="text-xs text-white px-2 py-1 rounded-full font-medium
                                         {{ $task->prioritas === 'HIGH'
                                            ? 'bg-red-500'
                                            : ($task->prioritas === 'MEDIUM' ? 'bg-yellow-500' : 'bg-blue-400') }}">
                                {{ ucfirst(strtolower($task->prioritas)) }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-3">
                            @if($task->status_task === 'TODO')
                                <span class="flex items-center gap-1 text-gray-500 text-xs font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <circle cx="12" cy="12" r="9" stroke-width="2"/>
                                    </svg>
                                    To Do
                                </span>
                            @elseif($task->status_task === 'DOING')
                                <span class="flex items-center gap-1 text-orange-500 text-xs font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0
                                                 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Doing
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-green-500 text-xs font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Done
                                </span>
                            @endif
                        </td>

                        {{-- Details --}}
                        <td class="px-5 py-3">
                            <a href="{{ route('tasks.edit', $task->id_task) }}"
                               class="text-orange-500 hover:underline text-xs font-medium">
                                Details ›
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">
                            Belum ada task.
                        </td>
                    </tr>
                @endforelse

                {{-- Row muncul saat search tidak ketemu --}}
                <tr id="noResultRow" class="hidden">
                    <td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">
                        Tidak ada task yang cocok dengan pencarian.
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Footer: total & pagination --}}
        <div id="tableFooter" class="flex items-center justify-between px-5 py-3 border-t border-gray-100">
            <span class="text-xs text-gray-400">
                Showing {{ $tasks->count() }} of {{ $tasks->total() }} active tasks
            </span>
            <div class="flex items-center gap-1 text-xs text-gray-500">
                @if($tasks->onFirstPage())
                    <span class="px-2 py-1 text-gray-300">‹</span>
                @else
                    <a href="{{ $tasks->previousPageUrl() }}"
                       class="px-2 py-1 hover:text-orange-500">‹</a>
                @endif

                <span class="px-2 py-1 text-orange-500 font-semibold">
                    Page {{ $tasks->currentPage() }} of {{ $tasks->lastPage() }}
                </span>

                @if($tasks->hasMorePages())
                    <a href="{{ $tasks->nextPageUrl() }}"
                       class="px-2 py-1 hover:text-orange-500">›</a>
                @else
                    <span class="px-2 py-1 text-gray-300">›</span>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Tombol tambah --}}
<a href="{{ route('kanban.index') }}"
   class="fixed bottom-6 right-6 w-12 h-12 bg-orange-500 hover:bg-orange-600
          rounded-full flex items-center justify-center shadow-lg text-white text-2xl
          transition z-40">
    +
</a>

<script>
const searchInput  = document.getElementById('searchInput');
const rows         = document.querySelectorAll('.task-row');
const noResultRow  = document.getElementById('noResultRow');
const tableFooter  = document.getElementById('tableFooter');

searchInput.addEventListener('input', function () {
    const keyword = this.value.toLowerCase().trim();
    let visibleCount = 0;

    rows.forEach(row => {
        const title = row.querySelector('.task-title').textContent.toLowerCase();
        if (title.includes(keyword)) {
            row.classList.remove('hidden');
            visibleCount++;
        } else {
            row.classList.add('hidden');
        }
    });

    // Tampilkan pesan tidak ada hasil
    if (visibleCount === 0) {
        noResultRow.classList.remove('hidden');
    } else {
        noResultRow.classList.add('hidden');
    }

    // Sembunyikan pagination saat search aktif
    if (keyword.length > 0) {
        tableFooter.classList.add('hidden');
    } else {
        tableFooter.classList.remove('hidden');
    }
});
</script>

@endsection