@extends('layouts.app')

@section('content')
<style>
.sortable-ghost {
    opacity: 0.35 !important;
    background-color: #faf5f0 !important;
    border: 2px dashed #bfa38a !important;
    box-shadow: none !important;
}
.sortable-drag {
    opacity: 0.95 !important;
    transform: rotate(2deg) scale(1.02) !important;
    box-shadow: 0 25px 50px -12px rgba(74, 39, 15, 0.22) !important;
    cursor: grabbing !important;
}
.sortable-fallback {
    opacity: 0.95 !important;
    transform: rotate(2deg) scale(1.02) !important;
    box-shadow: 0 25px 50px -12px rgba(74, 39, 15, 0.22) !important;
    cursor: grabbing !important;
}
</style>

<div class="flex flex-col">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- KOLOM KANBAN --}}
    <div class="flex gap-6 pb-6 items-start">

        {{-- TODO --}}
        <div class="flex-1 min-w-[280px] flex flex-col">
            <div class="flex items-center gap-2 mb-4">
                <h2 class="font-bold text-[#4a270f] text-base">To Do</h2>
                <span id="count-TODO" class="bg-[#eae0d5] text-[#8c7462] text-xs px-2.5 py-0.5 rounded-full font-bold">
                    {{ $todo->count() }}
                </span>
            </div>
            <div id="col-TODO" data-status="TODO"
                 class="kanban-col flex flex-col gap-4 flex-1 bg-[#fbf3e9] rounded-2xl p-4"
                 style="min-height: 80px;">
                @foreach($todo as $task)
                    @include('kanban.partials.card', ['task' => $task])
                @endforeach
            </div>
        </div>

        {{-- DOING --}}
        <div class="flex-1 min-w-[280px] flex flex-col">
            <div class="flex items-center gap-2 mb-4">
                <h2 class="font-bold text-[#4a270f] text-base">Doing</h2>
                <span id="count-DOING" class="bg-[#eae0d5] text-[#8c7462] text-xs px-2.5 py-0.5 rounded-full font-bold">
                    {{ $doing->count() }}
                </span>
            </div>
            <div id="col-DOING" data-status="DOING"
                 class="kanban-col flex flex-col gap-4 flex-1 bg-[#fbf3e9] rounded-2xl p-4"
                 style="min-height: 80px;">
                @foreach($doing as $task)
                    @include('kanban.partials.card', ['task' => $task])
                @endforeach
            </div>
        </div>

        {{-- DONE --}}
        <div class="flex-1 min-w-[280px] flex flex-col">
            <div class="flex items-center gap-2 mb-4">
                <h2 class="font-bold text-[#4a270f] text-base">Done</h2>
                <span id="count-DONE" class="bg-[#eae0d5] text-[#8c7462] text-xs px-2.5 py-0.5 rounded-full font-bold">
                    {{ $done->count() }}
                </span>
            </div>
            <div id="col-DONE" data-status="DONE"
                 class="kanban-col flex flex-col gap-4 flex-1 bg-[#fbf3e9] rounded-2xl p-4"
                 style="min-height: 80px;">
                @foreach($done as $task)
                    @include('kanban.partials.card', ['task' => $task])
                @endforeach
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
{{-- MODAL DELETE TASK --}}
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
{{-- MODAL TAMBAH TASK --}}
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
                <textarea name="deskripsi" rows="3" placeholder="Describe the core objectives of this task..." class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none"></textarea>
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
{{-- MODAL EDIT TASK --}}
{{-- ══════════════════════════════════════ --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-xl shadow-xl">
        <!-- Overdue Warning Banner -->
        <div id="editOverdueBanner" class="hidden" style="background-color: #ef4444; color: #ffffff; font-size: 11px; font-weight: 700; letter-spacing: 0.05em; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; margin: -2rem -2rem 1.5rem -2rem; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>THIS TASK IS PAST ITS DEADLINE</span>
        </div>

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
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Due Date</label>
                        <span id="editOverdueBadge" class="hidden" style="background-color: #ef4444; color: #ffffff; font-size: 9px; font-weight: 800; text-transform: uppercase; padding: 2px 8px; border-radius: 9999px; letter-spacing: 0.05em; line-height: 1.2;">Overdue</span>
                    </div>
                    <input type="date" id="editDeadline" name="tanggal_deadline"
                           class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all duration-200"/>
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
                <textarea id="editDeskripsi" name="deskripsi" rows="3" class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none"></textarea>
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

{{-- Data task untuk JS --}}
<script>
const tasksData = @json(array_merge(
    $todo->values()->toArray(),
    $doing->values()->toArray(),
    $done->values()->toArray()
));
</script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
// ══ MODAL CREATE ══
function openCreateModal() { document.getElementById('modalTambah').classList.remove('hidden'); }
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
    
    // Parse tanggal_deadline to YYYY-MM-DD
    let deadlineVal = '';
    if (task.tanggal_deadline) {
        const match = task.tanggal_deadline.match(/^\d{4}-\d{2}-\d{2}/);
        if (match) {
            deadlineVal = match[0];
        }
    }
    const deadlineInput = document.getElementById('editDeadline');
    deadlineInput.value = deadlineVal;

    document.getElementById('editDeskripsi').value = task.deskripsi ?? '';
    document.querySelectorAll('.edit-priority').forEach(r => r.checked = r.value === task.prioritas);
    const subtaskList = document.getElementById('subtaskListEdit');
    subtaskList.innerHTML = '';
    if (task.subtasks && task.subtasks.length > 0) {
        task.subtasks.forEach(sub => subtaskList.appendChild(makeSubtaskRow(sub.nama_subtask)));
    } else {
        subtaskList.appendChild(makeSubtaskRow(''));
    }

    // Overdue check logic
    const overdueBanner = document.getElementById('editOverdueBanner');
    const overdueBadge = document.getElementById('editOverdueBadge');

    function checkOverdue(dateStr) {
        if (!dateStr || task.status_task === 'DONE') return false;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const parts = dateStr.split('-');
        const deadlineDate = new Date(parts[0], parts[1] - 1, parts[2]);
        deadlineDate.setHours(0, 0, 0, 0);
        return deadlineDate < today;
    }

    function updateOverdueUI(dateStr) {
        const isOverdue = checkOverdue(dateStr);
        if (isOverdue) {
            overdueBanner.style.setProperty('display', 'flex', 'important');
            overdueBadge.style.setProperty('display', 'inline-block', 'important');
            deadlineInput.style.borderColor = '#ef4444';
            deadlineInput.style.color = '#ef4444';
        } else {
            overdueBanner.style.setProperty('display', 'none', 'important');
            overdueBadge.style.setProperty('display', 'none', 'important');
            deadlineInput.style.borderColor = '';
            deadlineInput.style.color = '';
        }
    }

    updateOverdueUI(deadlineVal);

    deadlineInput.oninput = function() {
        updateOverdueUI(this.value);
    };

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

// ══ DRAG AND DROP ══
document.querySelectorAll('.kanban-col').forEach(col => {
    Sortable.create(col, {
        group: 'kanban',
        animation: 200,
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        fallbackClass: 'sortable-fallback',
        forceFallback: true,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        onEnd: function (evt) {
            const taskId    = evt.item.dataset.taskId;
            const newStatus = evt.to.dataset.status;
            const oldStatus = evt.from.dataset.status;
            if (newStatus === oldStatus) return;
            updateCardStyle(evt.item, newStatus);
            updateCount();
            fetch(`/tasks/${taskId}/move`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PATCH',
                },
                body: JSON.stringify({ status_task: newStatus }),
            })
            .then(res => {
                if (!res.ok) { alert('Gagal memperbarui status.'); location.reload(); }
                else {
                    const task = tasksData.find(t => t.id_task === taskId);
                    if (task) task.status_task = newStatus;
                }
            })
            .catch(() => { alert('Terjadi kesalahan jaringan.'); location.reload(); });
        }
    });
});

function updateCardStyle(cardEl, newStatus) {
    // Border kiri (absolute element)
    const statusBorder = cardEl.querySelector('.status-border');
    const borderMap = { TODO: '#c026d3', DOING: '#1d4ed8', DONE: '#9ca3af' };
    if (statusBorder) {
        statusBorder.style.backgroundColor = borderMap[newStatus] ?? '#9ca3af';
    }

    // Opacity
    cardEl.classList.toggle('opacity-60', newStatus === 'DONE');

    // Title
    const titleEl = cardEl.querySelector('.task-title');
    if (titleEl) {
        titleEl.style.color          = newStatus === 'DONE' ? '#9ca3af' : '#2d1e17';
        titleEl.style.textDecoration = newStatus === 'DONE' ? 'line-through' : 'none';
    }

    // Desc
    const descEl = cardEl.querySelector('.task-desc');
    if (descEl) {
        descEl.style.color          = newStatus === 'DONE' ? '#9ca3af' : '#7c6a5e';
        descEl.style.textDecoration = newStatus === 'DONE' ? 'line-through' : 'none';
    }

    // Priority badge
    const badge    = cardEl.querySelector('.task-priority-badge');
    const priority = badge?.getAttribute('data-priority');
    if (badge) {
        const bgMap = {
            DONE: { HIGH: '#fee2e2', MEDIUM: '#f3e8ff', LOW: '#ffedd5' },
            ACTIVE: { HIGH: '#ff3b30', MEDIUM: '#a855f8', LOW: '#f97316' },
        };
        const colorMap = {
            DONE: { HIGH: '#ef4444', MEDIUM: '#a855f8', LOW: '#ea580c' },
        };
        if (newStatus === 'DONE') {
            badge.style.background = bgMap.DONE[priority] ?? '#ffedd5';
            badge.style.color      = colorMap.DONE[priority] ?? '#ea580c';
        } else {
            badge.style.background = bgMap.ACTIVE[priority] ?? '#f97316';
            badge.style.color      = '#ffffff';
        }
    }

    // Completed / date badge
    const completedBadge = cardEl.querySelector('.completed-badge');
    const dateBadge      = cardEl.querySelector('.date-badge');
    if (completedBadge) completedBadge.style.display = newStatus === 'DONE' ? 'flex' : 'none';
    if (dateBadge)      dateBadge.style.display = newStatus === 'DONE' ? 'none' : 'flex';
}

function updateCount() {
    ['TODO', 'DOING', 'DONE'].forEach(status => {
        const col   = document.getElementById(`col-${status}`);
        const badge = document.getElementById(`count-${status}`);
        if (col && badge) badge.textContent = col.querySelectorAll('[data-task-id]').length;
    });
}

['modalTambah', 'modalEdit', 'modalDelete'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});

// Intercept all subtask toggle form submissions for instant AJAX updates
document.addEventListener('submit', function (e) {
    if (e.target && e.target.action && e.target.action.includes('/subtasks/') && e.target.action.includes('/toggle')) {
        e.preventDefault(); // Prevent full page reload!

        const form = e.target;
        const button = form.querySelector('button');
        const spanText = form.closest('.flex').querySelector('span');

        // Send AJAX request
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-HTTP-Method-Override': 'PATCH',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const isCompleted = data.is_completed;
                if (!isCompleted) {
                    // Turn to uncompleted
                    button.style.backgroundColor = '#ffffff';
                    button.style.borderColor = '#bfa38a';
                    button.innerHTML = '';
                    if (spanText) {
                        spanText.style.color = '#4a270f';
                        spanText.style.fontWeight = '600';
                        spanText.style.textDecoration = 'none';
                    }
                } else {
                    // Turn to completed
                    button.style.backgroundColor = '#f97316';
                    button.style.borderColor = '#f97316';
                    button.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    `;
                    if (spanText) {
                        spanText.style.color = '#9ca3af';
                        spanText.style.fontWeight = '400';
                        spanText.style.textDecoration = 'line-through';
                    }
                }
            } else {
                alert('Gagal memperbarui subtask.');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan jaringan.');
        });
    }
});
</script>
@endsection