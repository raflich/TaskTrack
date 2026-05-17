@extends('layouts.app')

@section('content')
<div class="p-6 flex items-start justify-center">
    <div class="bg-white rounded-2xl p-8 w-full max-w-xl shadow-sm">
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
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Due Date</label>
                    <input type="date" name="tanggal_deadline"
                           value="{{ old('tanggal_deadline', $task->tanggal_deadline?->format('Y-m-d')) }}"
                           class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"/>
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
</script>
@endsection