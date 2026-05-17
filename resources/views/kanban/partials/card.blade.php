<div class="bg-white rounded-xl p-4 shadow-sm cursor-grab active:cursor-grabbing"
     data-task-id="{{ $task->id_task }}">

    {{-- Header --}}
    <div class="flex justify-between items-start mb-2">
        <h3 class="font-semibold text-gray-800 text-sm leading-snug flex-1 pr-2">
            {{ $task->judul_task }}
        </h3>
        <div class="flex gap-1 shrink-0">
            {{-- Edit --}}
            <button type="button"
                    onclick="openEditModal('{{ $task->id_task }}')"
                    class="text-gray-400 hover:text-orange-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                             m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>
            {{-- Hapus → buka modal delete --}}
            <button type="button"
                    onclick="openDeleteModal('{{ $task->id_task }}')"
                    class="text-gray-400 hover:text-red-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                             L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Deskripsi --}}
    @if($task->deskripsi)
        <p class="text-gray-400 text-xs mb-3 line-clamp-2">{{ $task->deskripsi }}</p>
    @endif

    {{-- Subtasks --}}
    @if($task->subtasks->count() > 0)
        <div class="mb-3 flex flex-col gap-1">
            @foreach($task->subtasks as $subtask)
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('subtasks.toggle', $subtask->id_subtask) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-4 h-4 rounded border shrink-0 flex items-center justify-center
                                       {{ $subtask->is_completed
                                          ? 'bg-orange-500 border-orange-500'
                                          : 'border-gray-300' }}">
                            @if($subtask->is_completed)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </button>
                    </form>
                    <span class="text-xs {{ $subtask->is_completed
                                            ? 'line-through text-gray-400'
                                            : 'text-gray-600' }}">
                        {{ $subtask->nama_subtask }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Footer: prioritas & deadline --}}
    <div class="flex items-center justify-between mt-2">
        <span class="text-xs text-white px-2 py-0.5 rounded-full font-medium
                     {{ $task->prioritas === 'HIGH'
                        ? 'bg-red-500'
                        : ($task->prioritas === 'MEDIUM' ? 'bg-yellow-500' : 'bg-blue-400') }}">
            {{ ucfirst(strtolower($task->prioritas)) }}
        </span>

        @if($task->tanggal_deadline)
            <span class="text-xs flex items-center gap-1
                         {{ $task->isOverdue() ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $task->tanggal_deadline->format('d M') }}
            </span>
        @endif
    </div>

</div>