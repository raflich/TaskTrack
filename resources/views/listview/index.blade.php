@extends('layouts.app')

@section('content')
<style>
    .task-row {
        transition: background 0.15s ease;
    }
    .task-row:hover {
        background-color: #faf5f0;
    }
    .task-row td:first-child {
        position: relative;
        padding-left: 0;
    }
    .row-border {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        border-radius: 0 2px 2px 0;
    }
</style>

<div class="p-6">

    {{-- Header --}}
    <h1 class="text-xl font-bold mb-1" style="color: #2d1e17; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">List View</h1>
    <p class="text-sm text-gray-400 mb-6" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">Manage your personal productivity across all workflows.</p>

    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter + Sort --}}
    <div class="flex gap-3 mb-4 flex-wrap items-center">
        <form method="GET" action="{{ route('listview.index') }}" class="flex gap-3">
            <select name="status" onchange="this.form.submit()"
                    class="rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 cursor-pointer"
                    style="border: 1px solid #e8d8c8; background: #fff; color: #4a270f; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                <option value="all"   {{ !request('status') || request('status') === 'all' ? 'selected' : '' }}>Filter Status</option>
                <option value="TODO"  {{ request('status') === 'TODO'  ? 'selected' : '' }}>To Do</option>
                <option value="DOING" {{ request('status') === 'DOING' ? 'selected' : '' }}>Doing</option>
                <option value="DONE"  {{ request('status') === 'DONE'  ? 'selected' : '' }}>Done</option>
            </select>

            <select name="sort" onchange="this.form.submit()"
                    class="rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 cursor-pointer"
                    style="border: 1px solid #e8d8c8; background: #fff; color: #4a270f; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                <option value="due_date" {{ !request('sort') || request('sort') === 'due_date' ? 'selected' : '' }}>Sort: Due Date</option>
                <option value="priority" {{ request('sort') === 'priority' ? 'selected' : '' }}>Sort: Priority</option>
                <option value="title"    {{ request('sort') === 'title'    ? 'selected' : '' }}>Sort: Title</option>
            </select>
        </form>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl shadow-sm overflow-hidden" style="background: #fff;">
        <table class="w-full text-sm" style="border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #f0e6d3;">
                    <th class="text-left py-3 font-semibold text-xs uppercase tracking-wider"
                        style="padding-left: 20px; color: #8c7462; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; width: 38%;">Task Title</th>
                    <th class="text-left px-5 py-3 font-semibold text-xs uppercase tracking-wider"
                        style="color: #8c7462; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; width: 15%;">Due Date</th>
                    <th class="text-left px-5 py-3 font-semibold text-xs uppercase tracking-wider"
                        style="color: #8c7462; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; width: 12%;">Priority</th>
                    <th class="text-left px-5 py-3 font-semibold text-xs uppercase tracking-wider"
                        style="color: #8c7462; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; width: 15%;">Status</th>
                    <th class="text-left px-5 py-3 font-semibold text-xs uppercase tracking-wider"
                        style="color: #8c7462; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; width: 20%;">Details</th>
                </tr>
            </thead>
            <tbody id="taskTableBody">
                @forelse($tasks as $task)
                    @php
                        $borderColor = match($task->status_task) {
                            'TODO'  => '#c026d3',
                            'DOING' => '#1d4ed8',
                            default => '#9ca3af',
                        };

                        $isDone = $task->status_task === 'DONE';

                        $priorityBg = match(true) {
                            !$isDone && $task->prioritas === 'HIGH'   => '#ff3b30',
                            !$isDone && $task->prioritas === 'MEDIUM' => '#a855f8',
                            !$isDone                                  => '#f97316',
                            $task->prioritas === 'HIGH'               => '#fee2e2',
                            $task->prioritas === 'MEDIUM'             => '#f3e8ff',
                            default                                   => '#ffedd5',
                        };

                        $priorityColor = match(true) {
                            !$isDone              => '#ffffff',
                            $task->prioritas === 'HIGH'   => '#ef4444',
                            $task->prioritas === 'MEDIUM' => '#a855f8',
                            default               => '#ea580c',
                        };
                    @endphp

                    <tr class="task-row {{ $isDone ? 'opacity-60' : '' }}"
                        style="border-bottom: 1px solid #faf0e6;">

                        {{-- Task Title with left border accent --}}
                        <td style="padding: 14px 16px 14px 0; position: relative;">
                            <div class="row-border" style="background-color: {{ $borderColor }};"></div>
                            <div class="flex items-center gap-3" style="padding-left: 16px;">
                                <span class="task-title font-semibold"
                                      style="color: {{ $isDone ? '#9ca3af' : '#2d1e17' }};
                                             {{ $isDone ? 'text-decoration: line-through;' : '' }}
                                             font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
                                             font-size: 13.5px;">
                                    {{ $task->judul_task }}
                                </span>
                            </div>
                            @if($task->deskripsi)
                                <p class="text-xs mt-0.5 line-clamp-1"
                                   style="padding-left: 16px; color: {{ $isDone ? '#c4b5a8' : '#a08878' }};
                                          font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                                    {{ $task->deskripsi }}
                                </p>
                            @endif
                        </td>

                        {{-- Due Date --}}
                        <td class="px-5" style="padding-top: 14px; padding-bottom: 14px;">
                            @if($task->tanggal_deadline)
                                <span class="flex items-center gap-1.5 text-xs"
                                      style="color: {{ $task->isOverdue() && !$isDone ? '#ef4444' : '#7c6a5e' }};
                                             font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                         stroke="{{ $task->isOverdue() && !$isDone ? '#ef4444' : '#bfa38a' }}"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    {{ $task->tanggal_deadline->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-xs" style="color: #c4b5a8; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">—</span>
                            @endif
                        </td>

                        {{-- Priority --}}
                        <td class="px-5" style="padding-top: 14px; padding-bottom: 14px;">
                            <span class="text-[11px] font-bold tracking-wide"
                                  style="background-color: {{ $priorityBg }};
                                         color: {{ $priorityColor }};
                                         padding: 4px 12px;
                                         border-radius: 9999px;
                                         font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
                                         display: inline-block;">
                                {{ ucfirst(strtolower($task->prioritas)) }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-5" style="padding-top: 14px; padding-bottom: 14px;">
                            @if($task->status_task === 'TODO')
                                <span class="flex items-center gap-1.5 text-xs font-semibold"
                                      style="color: #c026d3; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#c026d3" stroke-width="2.2">
                                        <circle cx="12" cy="12" r="9"/>
                                    </svg>
                                    To Do
                                </span>
                            @elseif($task->status_task === 'DOING')
                                <span class="flex items-center gap-1.5 text-xs font-semibold"
                                      style="color: #1d4ed8; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Doing
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-xs font-semibold"
                                      style="color: #9ca3af; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                    Done
                                </span>
                            @endif
                        </td>

                        {{-- Details & Actions --}}
                        <td class="px-5" style="padding-top: 14px; padding-bottom: 14px;">
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="openEditModal('{{ $task->id_task }}')"
                                        class="text-xs font-semibold hover:underline transition"
                                        style="background: none; border: none; padding: 0; cursor: pointer; color: #f97316; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                                    Details ›
                                </button>
                                <button type="button" onclick="openDeleteModal('{{ $task->id_task }}')"
                                        class="hover:opacity-70 transition-opacity"
                                        style="background: none; border: none; padding: 0; cursor: pointer; display: flex; align-items: center;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                    </svg>
                                </button>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="5" class="px-5 py-10 text-center text-sm" style="color: #bfa38a; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                            Belum ada task.
                        </td>
                    </tr>
                @endforelse

                {{-- No result from search --}}
                <tr id="noResultRow" class="hidden">
                    <td colspan="5" class="px-5 py-10 text-center text-sm" style="color: #bfa38a; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                        Tidak ada task yang cocok dengan pencarian.
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Footer: total & pagination --}}
        <div id="tableFooter" class="flex items-center justify-between px-5 py-3"
             style="border-top: 1px solid #f0e6d3;">
            <span class="text-xs" style="color: #bfa38a; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                Showing {{ $tasks->count() }} of {{ $tasks->total() }} tasks
            </span>
            <div class="flex items-center gap-1 text-xs" style="color: #8c7462; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                @if($tasks->onFirstPage())
                    <span class="px-2 py-1" style="color: #d1c4b8;">‹</span>
                @else
                    <a href="{{ $tasks->previousPageUrl() }}"
                       class="px-2 py-1 hover:text-orange-500 transition">‹</a>
                @endif

                <span class="px-2 py-1 font-semibold" style="color: #f97316;">
                    Page {{ $tasks->currentPage() }} of {{ $tasks->lastPage() }}
                </span>

                @if($tasks->hasMorePages())
                    <a href="{{ $tasks->nextPageUrl() }}"
                       class="px-2 py-1 hover:text-orange-500 transition">›</a>
                @else
                    <span class="px-2 py-1" style="color: #d1c4b8;">›</span>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Tombol tambah --}}
<button onclick="openCreateModal()"
        class="fixed bottom-6 right-6 w-12 h-12 bg-orange-500 hover:bg-orange-600
               rounded-full flex items-center justify-center shadow-lg text-white text-2xl
               transition z-40">
    +
</button>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL DELETE --}}
{{-- ══════════════════════════════════════ --}}
<div id="modalDelete" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-xl text-center">
        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                         L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h2 class="text-lg font-bold text-gray-800 mb-2">Delete Task?</h2>
        <p class="text-sm text-gray-400 mb-6">
            Are you sure you want to delete this task?<br/>
            This action will move it to the Trash.
        </p>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold
                           py-2.5 rounded-xl text-sm transition mb-3">
                Move to Trash
            </button>
        </form>
        <button onclick="closeDeleteModal()"
                class="text-sm text-gray-400 hover:text-gray-600 transition">
            Cancel
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL CREATE --}}
{{-- ══════════════════════════════════════ --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-xl shadow-xl">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Create New Task</h2>
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Task Title</label>
                <input type="text" name="judul_task"
                       placeholder="e.g., Finalize Brand Guidelines"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400"
                       required/>
            </div>
            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Due Date</label>
                    <input type="date" name="tanggal_deadline"
                           class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"/>
                </div>
                <div class="flex-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Priority</label>
                    <div class="flex gap-2 mt-1">
                        @foreach(['LOW' => 'Low', 'MEDIUM' => 'Medium', 'HIGH' => 'High'] as $val => $label)
                            <label class="flex-1 text-center border border-gray-200 rounded-lg py-2 text-sm text-gray-600 cursor-pointer hover:border-orange-400 has-[:checked]:border-orange-500 has-[:checked]:text-orange-500 has-[:checked]:font-semibold transition">
                                <input type="radio" name="prioritas" value="{{ $val }}" class="hidden"
                                       {{ $val === 'MEDIUM' ? 'checked' : '' }}/>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Description</label>
                <textarea name="deskripsi" rows="3" placeholder="Describe the core objectives of this task..."
                          class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none"></textarea>
            </div>
            <div class="mb-6">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Subtasks</label>
                <div id="subtaskListCreate" class="flex flex-col gap-2 mt-2">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 border border-gray-300 rounded shrink-0"></div>
                        <input type="text" name="subtasks[]" placeholder="Add a subtask..."
                               class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400"/>
                    </div>
                </div>
                <button type="button" onclick="addSubtaskCreate()"
                        class="mt-2 text-orange-500 text-sm flex items-center gap-1 hover:underline">
                    + Add Subtask
                </button>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeCreateModal()"
                        class="px-5 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit"
                        class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL EDIT --}}
{{-- ══════════════════════════════════════ --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-xl shadow-xl">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Task</h2>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Task Title</label>
                <input type="text" id="editJudul" name="judul_task"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400"
                       required/>
            </div>
            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Due Date</label>
                    <input type="date" id="editDeadline" name="tanggal_deadline"
                           class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"/>
                </div>
                <div class="flex-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Priority</label>
                    <div class="flex gap-2 mt-1">
                        @foreach(['LOW' => 'Low', 'MEDIUM' => 'Medium', 'HIGH' => 'High'] as $val => $label)
                            <label class="flex-1 text-center border border-gray-200 rounded-lg py-2 text-sm text-gray-600 cursor-pointer hover:border-orange-400 has-[:checked]:border-orange-500 has-[:checked]:text-orange-500 has-[:checked]:font-semibold transition">
                                <input type="radio" name="prioritas" value="{{ $val }}"
                                       class="edit-priority hidden"/>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Description</label>
                <textarea id="editDeskripsi" name="deskripsi" rows="3"
                          class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none"></textarea>
            </div>
            <div class="mb-6">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Subtasks</label>
                <div id="subtaskListEdit" class="flex flex-col gap-2 mt-2"></div>
                <button type="button" onclick="addSubtaskEdit()"
                        class="mt-2 text-orange-500 text-sm flex items-center gap-1 hover:underline">
                    + Add Subtask
                </button>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()"
                        class="px-5 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit"
                        class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Task data for JS --}}
<script>
const tasksData = @json($tasks->items());
</script>

<script>
// ══ MODAL CREATE ══
function openCreateModal()  { document.getElementById('modalTambah').classList.remove('hidden'); }
function closeCreateModal() { document.getElementById('modalTambah').classList.add('hidden'); }
function addSubtaskCreate() {
    const list = document.getElementById('subtaskListCreate');
    const div  = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <div class="w-4 h-4 border border-gray-300 rounded shrink-0"></div>
        <input type="text" name="subtasks[]" placeholder="Add a subtask..."
               class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400"/>
    `;
    list.appendChild(div);
}

// ══ MODAL DELETE ══
function openDeleteModal(taskId) {
    document.getElementById('deleteForm').action = `/tasks/${taskId}`;
    document.getElementById('modalDelete').classList.remove('hidden');
}
function closeDeleteModal() { document.getElementById('modalDelete').classList.add('hidden'); }

// ══ MODAL EDIT ══
function openEditModal(taskId) {
    const task = tasksData.find(t => t.id_task === taskId);
    if (!task) return;
    document.getElementById('editForm').action     = `/tasks/${taskId}`;
    document.getElementById('editJudul').value     = task.judul_task;
    document.getElementById('editDeadline').value  = task.tanggal_deadline ?? '';
    document.getElementById('editDeskripsi').value = task.deskripsi ?? '';
    document.querySelectorAll('.edit-priority').forEach(r => r.checked = r.value === task.prioritas);
    const subtaskList = document.getElementById('subtaskListEdit');
    subtaskList.innerHTML = '';
    if (task.subtasks && task.subtasks.length > 0) {
        task.subtasks.forEach(sub => subtaskList.appendChild(makeSubtaskRow(sub.nama_subtask)));
    } else {
        subtaskList.appendChild(makeSubtaskRow(''));
    }
    document.getElementById('modalEdit').classList.remove('hidden');
}
function closeEditModal() { document.getElementById('modalEdit').classList.add('hidden'); }
function makeSubtaskRow(value = '') {
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <div class="w-4 h-4 border border-gray-300 rounded shrink-0"></div>
        <input type="text" name="subtasks[]" value="${value}" placeholder="Add a subtask..."
               class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400"/>
    `;
    return div;
}
function addSubtaskEdit() { document.getElementById('subtaskListEdit').appendChild(makeSubtaskRow('')); }

// Close modals on backdrop click
['modalTambah', 'modalEdit', 'modalDelete'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>

@endsection