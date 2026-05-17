@extends('layouts.app')

@section('content')
<div class="p-6 h-full flex flex-col">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- KOLOM KANBAN --}}
    <div class="flex gap-4 flex-1 overflow-x-auto pb-4">

        {{-- TODO --}}
        <div class="flex-1 min-w-[260px] flex flex-col">
            <div class="flex items-center gap-2 mb-3">
                <h2 class="font-semibold text-gray-700">To Do</h2>
                <span id="count-TODO" class="bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full font-medium">
                    {{ $todo->count() }}
                </span>
            </div>
            <div id="col-TODO" data-status="TODO"
                 class="kanban-col flex flex-col gap-3 flex-1 bg-[#f0e6dc] rounded-xl p-3 min-h-[200px]">
                @foreach($todo as $task)
                    @include('kanban.partials.card', ['task' => $task])
                @endforeach
            </div>
        </div>

        {{-- DOING --}}
        <div class="flex-1 min-w-[260px] flex flex-col">
            <div class="flex items-center gap-2 mb-3">
                <h2 class="font-semibold text-gray-700">Doing</h2>
                <span id="count-DOING" class="bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full font-medium">
                    {{ $doing->count() }}
                </span>
            </div>
            <div id="col-DOING" data-status="DOING"
                 class="kanban-col flex flex-col gap-3 flex-1 bg-[#f0e6dc] rounded-xl p-3 min-h-[200px]">
                @foreach($doing as $task)
                    @include('kanban.partials.card', ['task' => $task])
                @endforeach
            </div>
        </div>

        {{-- DONE --}}
        <div class="flex-1 min-w-[260px] flex flex-col">
            <div class="flex items-center gap-2 mb-3">
                <h2 class="font-semibold text-gray-700">Done</h2>
                <span id="count-DONE" class="bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full font-medium">
                    {{ $done->count() }}
                </span>
            </div>
            <div id="col-DONE" data-status="DONE"
                 class="kanban-col flex flex-col gap-3 flex-1 bg-[#f0e6dc] rounded-xl p-3 min-h-[200px]">
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

        {{-- Icon trash --}}
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

        {{-- Form delete --}}
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
                        class="px-5 py-2 text-sm text-gray-500 hover:text-gray-700">
                    Cancel
                </button>
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
                        class="px-5 py-2 text-sm text-gray-500 hover:text-gray-700">
                    Cancel
                </button>
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

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
// ══ MODAL CREATE ══
function openCreateModal() {
    document.getElementById('modalTambah').classList.remove('hidden');
}
function closeCreateModal() {
    document.getElementById('modalTambah').classList.add('hidden');
}
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
    const url = `/tasks/${taskId}`;
    document.getElementById('deleteForm').action = url;
    document.getElementById('modalDelete').classList.remove('hidden');
}
function closeDeleteModal() {
    document.getElementById('modalDelete').classList.add('hidden');
}

// ══ MODAL EDIT ══
function openEditModal(taskId) {
    const task = tasksData.find(t => t.id_task === taskId);
    if (!task) return;

    document.getElementById('editForm').action = `/tasks/${taskId}`;
    document.getElementById('editJudul').value    = task.judul_task;
    document.getElementById('editDeadline').value = task.tanggal_deadline ?? '';
    document.getElementById('editDeskripsi').value = task.deskripsi ?? '';

    document.querySelectorAll('.edit-priority').forEach(radio => {
        radio.checked = radio.value === task.prioritas;
    });

    const subtaskList = document.getElementById('subtaskListEdit');
    subtaskList.innerHTML = '';
    if (task.subtasks && task.subtasks.length > 0) {
        task.subtasks.forEach(sub => {
            subtaskList.appendChild(makeSubtaskRow(sub.nama_subtask));
        });
    } else {
        subtaskList.appendChild(makeSubtaskRow(''));
    }

    document.getElementById('modalEdit').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}
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
function addSubtaskEdit() {
    document.getElementById('subtaskListEdit').appendChild(makeSubtaskRow(''));
}

// ══ DRAG AND DROP ══
document.querySelectorAll('.kanban-col').forEach(col => {
    Sortable.create(col, {
        group: 'kanban',
        animation: 150,
        ghostClass: 'opacity-40',
        dragClass: 'shadow-2xl',
        onEnd: function (evt) {
            const taskId    = evt.item.dataset.taskId;
            const newStatus = evt.to.dataset.status;
            const oldStatus = evt.from.dataset.status;

            if (newStatus === oldStatus) return;

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
                if (!res.ok) {
                    alert('Gagal memperbarui status.');
                    location.reload();
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan jaringan.');
                location.reload();
            });
        }
    });
});

function updateCount() {
    ['TODO', 'DOING', 'DONE'].forEach(status => {
        const col   = document.getElementById(`col-${status}`);
        const badge = document.getElementById(`count-${status}`);
        badge.textContent = col.querySelectorAll('[data-task-id]').length;
    });
}

// ══ TUTUP MODAL KLIK LUAR ══
['modalTambah', 'modalEdit', 'modalDelete'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
@endsection