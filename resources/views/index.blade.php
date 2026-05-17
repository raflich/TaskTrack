@extends('layouts.app')

@section('content')
<div class="p-6 h-full flex flex-col">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- KANBAN COLUMNS --}}
    <div class="flex gap-4 flex-1 overflow-x-auto">

        {{-- Kolom TODO --}}
        <div class="flex-1 min-w-[260px] flex flex-col">
            <div class="flex items-center gap-2 mb-3">
                <h2 class="font-semibold text-gray-700">To Do</h2>
                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">
                    {{ $todo->count() }}
                </span>
            </div>
            <div class="flex flex-col gap-3 flex-1 bg-[#f5ece4] rounded-xl p-3">
                @foreach($todo as $task)
                    @include('kanban.partials.card', ['task' => $task])
                @endforeach
            </div>
        </div>

        {{-- Kolom DOING --}}
        <div class="flex-1 min-w-[260px] flex flex-col">
            <div class="flex items-center gap-2 mb-3">
                <h2 class="font-semibold text-gray-700">Doing</h2>
                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">
                    {{ $doing->count() }}
                </span>
            </div>
            <div class="flex flex-col gap-3 flex-1 bg-[#f5ece4] rounded-xl p-3">
                @foreach($doing as $task)
                    @include('kanban.partials.card', ['task' => $task])
                @endforeach
            </div>
        </div>

        {{-- Kolom DONE --}}
        <div class="flex-1 min-w-[260px] flex flex-col">
            <div class="flex items-center gap-2 mb-3">
                <h2 class="font-semibold text-gray-700">Done</h2>
                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">
                    {{ $done->count() }}
                </span>
            </div>
            <div class="flex flex-col gap-3 flex-1 bg-[#f5ece4] rounded-xl p-3">
                @foreach($done as $task)
                    @include('kanban.partials.card', ['task' => $task])
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- Tombol tambah task --}}
<button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
        class="fixed bottom-6 right-6 w-12 h-12 bg-orange-500 hover:bg-orange-600
               rounded-full flex items-center justify-center shadow-lg text-white text-2xl">
    +
</button>

{{-- MODAL TAMBAH TASK --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black/40 flex items-center
                              justify-center z-50">
    <div class="bg-white rounded-2xl p-8 w-full max-w-xl shadow-xl">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Create New Task</h2>
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            {{-- Judul --}}
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Task Title
                </label>
                <input type="text" name="judul_task"
                       placeholder="e.g., Finalize Brand Guidelines"
                       class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2
                              text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                       required/>
            </div>

            {{-- Due Date & Priority --}}
            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Due Date
                    </label>
                    <input type="date" name="tanggal_deadline"
                           class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2
                                  text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"/>
                </div>
                <div class="flex-1">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Priority
                    </label>
                    <div class="flex gap-2 mt-1">
                        @foreach(['LOW','MEDIUM','HIGH'] as $p)
                            <label class="flex-1 text-center border border-gray-200 rounded-lg
                                          py-2 text-sm cursor-pointer has-[:checked]:border-orange-500
                                          has-[:checked]:text-orange-500">
                                <input type="radio" name="prioritas" value="{{ $p }}"
                                       class="hidden"
                                       {{ $p === 'MEDIUM' ? 'checked' : '' }}/>
                                {{ ucfirst(strtolower($p)) }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Description
                </label>
                <textarea name="deskripsi" rows="3"
                          placeholder="Describe the core objectives of this task..."
                          class="mt-1 w-full border border-gray-200 rounded-lg px-4 py-2
                                 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                </textarea>
            </div>

            {{-- Subtasks --}}
            <div class="mb-6">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Subtasks
                </label>
                <div id="subtaskList" class="flex flex-col gap-2 mt-2">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 border border-gray-300 rounded"></div>
                        <input type="text" name="subtasks[]"
                               placeholder="Add a subtask..."
                               class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5
                                      text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"/>
                    </div>
                </div>
                <button type="button" onclick="addSubtask()"
                        class="mt-2 text-orange-500 text-sm flex items-center gap-1 hover:underline">
                    + Add Subtask
                </button>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="document.getElementById('modalTambah').classList.add('hidden')"
                        class="px-5 py-2 text-sm text-gray-500 hover:text-gray-700">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white
                               text-sm font-semibold rounded-lg">
                    Create Task
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
        <div class="w-4 h-4 border border-gray-300 rounded"></div>
        <input type="text" name="subtasks[]" placeholder="Add a subtask..."
               class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm
                      focus:outline-none focus:ring-2 focus:ring-orange-400"/>
    `;
    list.appendChild(div);
}
</script>
@endsection