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
                <div id="subtaskList" class="flex flex-col gap-2 mt-2">
                    @forelse($task->subtasks as $subtask)
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border border-gray-300 rounded shrink-0"></div>
                            <input type="text" name="subtasks[]"
                                   value="{{ $subtask->nama_subtask }}"
                                   class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"/>
                        </div>
                    @empty
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 border border-gray-300 rounded shrink-0"></div>
                            <input type="text" name="subtasks[]"
                                   placeholder="Add a subtask..."
                                   class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400"/>
                        </div>
                    @endforelse
                </div>
                <button type="button" onclick="addSubtask()"
                        class="mt-2 text-orange-500 text-sm flex items-center gap-1 hover:underline">
                    + Add Subtask
                </button>
            </div>

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
function addSubtask() {
    const list = document.getElementById('subtaskList');
    const div  = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <div class="w-4 h-4 border border-gray-300 rounded shrink-0"></div>
        <input type="text" name="subtasks[]" placeholder="Add a subtask..."
               class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400"/>
    `;
    list.appendChild(div);
}

// Live overdue check
document.addEventListener('DOMContentLoaded', function() {
    const deadlineInput = document.getElementById('editDeadline');
    const overdueBanner = document.getElementById('editOverdueBanner');
    const overdueBadge = document.getElementById('editOverdueBadge');
    const statusTask = @json($task->status_task);

    function checkOverdue(dateStr) {
        if (!dateStr || statusTask === 'DONE') return false;
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

    if (deadlineInput) {
        deadlineInput.addEventListener('input', function() {
            updateOverdueUI(this.value);
        });
    }
});
</script>
@endsection