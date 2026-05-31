@extends('layouts.app')

@section('content')
<div class="p-6 flex items-start justify-center">
    <div class="bg-white rounded-2xl p-8 w-full max-w-xl shadow-sm relative overflow-hidden">
        @php
            $isOverdue = $task->tanggal_deadline && $task->tanggal_deadline->isPast() && $task->status_task !== 'DONE';
        @endphp

        <!-- Overdue Warning Banner -->
        <div id="editOverdueBanner" class="{{ $isOverdue ? '' : 'hidden' }}" style="background-color: #ef4444; color: #ffffff; font-size: 11px; font-weight: 700; letter-spacing: 0.05em; display: {{ $isOverdue ? 'flex' : 'none' }}; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; margin: -2rem -2rem 1.5rem -2rem; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>THIS TASK IS PAST ITS DEADLINE</span>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Task</h2>

        <form method="POST" action="{{ route('tasks.update', $task->id_task) }}">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Task Title</label>
                <input type="text" name="judul_task"
                       value="{{ old('judul_task', $task->judul_task) }}"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400"
                       required/>
            </div>

            {{-- Due Date & Priority --}}
            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Due Date</label>
                        <span id="editOverdueBadge" class="{{ $isOverdue ? '' : 'hidden' }}" style="background-color: #ef4444; color: #ffffff; font-size: 9px; font-weight: 800; text-transform: uppercase; padding: 2px 8px; border-radius: 9999px; letter-spacing: 0.05em; line-height: 1.2; display: {{ $isOverdue ? 'inline-block' : 'none' }};">Overdue</span>
                    </div>
                    <input type="date" id="editDeadline" name="tanggal_deadline"
                           value="{{ old('tanggal_deadline', $task->tanggal_deadline?->format('Y-m-d')) }}"
                           class="mt-1 w-full border {{ $isOverdue ? 'border-red-500 text-red-500 focus:ring-red-400' : 'border-gray-200 text-gray-800 focus:ring-orange-400' }} rounded-lg px-4 py-2 text-sm bg-white focus:outline-none focus:ring-2 transition-all duration-200"
                           style="{{ $isOverdue ? 'border-color: #ef4444; color: #ef4444;' : '' }}"/>
                </div>
                <div class="flex-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Priority</label>
                    <div class="flex gap-2 mt-1">
                        @foreach(['LOW' => 'Low', 'MEDIUM' => 'Medium', 'HIGH' => 'High'] as $val => $label)
                            <label class="flex-1 text-center border border-gray-200 rounded-lg py-2 text-sm text-gray-600 cursor-pointer hover:border-orange-400 has-[:checked]:border-orange-500 has-[:checked]:text-orange-500 has-[:checked]:font-semibold transition">
                                <input type="radio" name="prioritas" value="{{ $val }}" class="hidden"
                                       {{ old('prioritas', $task->prioritas) === $val ? 'checked' : '' }}/>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Description</label>
                <textarea name="deskripsi" rows="3" class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none">{{ old('deskripsi', $task->deskripsi) }}</textarea>
            </div>

            {{-- Subtasks --}}
            <div class="mb-6">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Subtasks</label>

                {{-- Existing subtasks with toggleable checkbox --}}
                <div id="existingSubtaskList" class="flex flex-col gap-2 mt-2">
                    @foreach($task->subtasks as $subtask)
                        <div class="flex items-center gap-2 subtask-existing-row" data-subtask-id="{{ $subtask->id_subtask }}">
                            {{-- Hidden fields to pass id & name on form submit --}}
                            <input type="hidden" name="subtask_ids[]" value="{{ $subtask->id_subtask }}"/>
                            <input type="hidden" name="subtask_names[]" value="{{ $subtask->nama_subtask }}" class="subtask-name-val"/>

                            {{-- AJAX Toggle Checkbox --}}
                            <button type="button"
                                    onclick="toggleSubtask(this, '{{ $subtask->id_subtask }}')"
                                    class="subtask-toggle-btn rounded shrink-0 flex items-center justify-center transition-all"
                                    data-completed="{{ $subtask->is_completed ? '1' : '0' }}"
                                    style="width: 18px; height: 18px;
                                           border: 1.5px solid {{ $subtask->is_completed ? '#f97316' : '#bfa38a' }};
                                           background: {{ $subtask->is_completed ? '#f97316' : '#ffffff' }};
                                           display: flex; align-items: center; justify-content: center;
                                           padding: 0; cursor: pointer; border-radius: 4px; flex-shrink: 0;">
                                @if($subtask->is_completed)
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                @endif
                            </button>

                            {{-- Editable name --}}
                            <input type="text"
                                   value="{{ $subtask->nama_subtask }}"
                                   oninput="this.closest('.subtask-existing-row').querySelector('.subtask-name-val').value = this.value"
                                   class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"
                                   style="color: {{ $subtask->is_completed ? '#9ca3af' : '#1f2937' }};
                                          text-decoration: {{ $subtask->is_completed ? 'line-through' : 'none' }};"
                                   placeholder="Subtask name..."/>

                            {{-- Remove row button --}}
                            <button type="button" onclick="removeExistingSubtask(this, '{{ $subtask->id_subtask }}')"
                                    class="text-gray-300 hover:text-red-400 transition shrink-0" style="background:none;border:none;cursor:pointer;padding:2px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- New subtasks (no ID yet) --}}
                <div id="newSubtaskList" class="flex flex-col gap-2 mt-1"></div>

                <button type="button" onclick="addNewSubtask()"
                        class="mt-2 text-orange-500 text-sm flex items-center gap-1 hover:underline">
                    + Add Subtask
                </button>
            </div>

            {{-- Deleted existing subtask IDs (to remove from hidden list) - managed by JS --}}
            <div id="deletedSubtaskContainer"></div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('kanban.index') }}"
                   class="px-5 py-2 text-sm text-gray-500 hover:text-gray-700">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Toggle existing subtask via AJAX ──────────────────────────────
function toggleSubtask(btn, subtaskId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    fetch(`/subtasks/${subtaskId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-HTTP-Method-Override': 'PATCH',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) { alert('Gagal memperbarui subtask.'); return; }
        const isCompleted = data.is_completed;
        btn.setAttribute('data-completed', isCompleted ? '1' : '0');
        const row = btn.closest('.subtask-existing-row');
        const nameInput = row.querySelector('input[type="text"]');

        if (isCompleted) {
            btn.style.backgroundColor = '#f97316';
            btn.style.borderColor     = '#f97316';
            btn.innerHTML = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`;
            if (nameInput) { nameInput.style.color = '#9ca3af'; nameInput.style.textDecoration = 'line-through'; }
        } else {
            btn.style.backgroundColor = '#ffffff';
            btn.style.borderColor     = '#bfa38a';
            btn.innerHTML = '';
            if (nameInput) { nameInput.style.color = '#1f2937'; nameInput.style.textDecoration = 'none'; }
        }
    })
    .catch(() => alert('Terjadi kesalahan jaringan.'));
}

// ── Remove existing subtask row (removes from form, won't be sent) ──
function removeExistingSubtask(btn, subtaskId) {
    const row = btn.closest('.subtask-existing-row');
    // Remove the hidden inputs so they aren't submitted
    row.querySelectorAll('input[type="hidden"]').forEach(el => el.remove());
    row.remove();
}

// ── Add a new subtask row ─────────────────────────────────────────
function addNewSubtask() {
    const list = document.getElementById('newSubtaskList');
    const div  = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <div style="width:18px;height:18px;border:1.5px solid #d1d5db;border-radius:4px;flex-shrink:0;"></div>
        <input type="text" name="new_subtasks[]" placeholder="Add a subtask..."
               class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400"/>
        <button type="button" onclick="this.closest('div.flex').remove()"
                class="text-gray-300 hover:text-red-400 transition" style="background:none;border:none;cursor:pointer;padding:2px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    `;
    list.appendChild(div);
}

// ── Live overdue check ────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    const deadlineInput = document.getElementById('editDeadline');
    const overdueBanner = document.getElementById('editOverdueBanner');
    const overdueBadge  = document.getElementById('editOverdueBadge');
    const statusTask    = @json($task->status_task);

    function checkOverdue(dateStr) {
        if (!dateStr || statusTask === 'DONE') return false;
        const today = new Date(); today.setHours(0,0,0,0);
        const parts = dateStr.split('-');
        const dl = new Date(parts[0], parts[1]-1, parts[2]); dl.setHours(0,0,0,0);
        return dl < today;
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

    if (deadlineInput) {
        deadlineInput.addEventListener('input', function() { updateOverdueUI(this.value); });
    }
});
</script>
@endsection