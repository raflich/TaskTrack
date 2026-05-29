@php
    $isOverdue = $task->tanggal_deadline && $task->isOverdue() && $task->status_task !== 'DONE';

    $borderColor = $isOverdue
        ? '#ef4444'
        : match($task->status_task) {
            'TODO'  => '#c026d3',
            'DOING' => '#1d4ed8',
            default => '#9ca3af',
        };

    $priorityBg = match(true) {
        $task->status_task !== 'DONE' && $task->prioritas === 'HIGH'   => '#ff3b30',
        $task->status_task !== 'DONE' && $task->prioritas === 'MEDIUM' => '#a855f8',
        $task->status_task !== 'DONE'                                  => '#f97316',
        $task->prioritas === 'HIGH'   => '#fee2e2',
        $task->prioritas === 'MEDIUM' => '#f3e8ff',
        default                       => '#ffedd5',
    };

    $priorityColor = match(true) {
        $task->status_task !== 'DONE' => '#ffffff',
        $task->prioritas === 'HIGH'   => '#ef4444',
        $task->prioritas === 'MEDIUM' => '#a855f8',
        default                       => '#ea580c',
    };
@endphp

<div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 cursor-grab active:cursor-grabbing task-card {{ $task->status_task === 'DONE' ? 'opacity-60' : '' }}"
     style="padding: 20px; min-height: 120px; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden;"
     data-task-id="{{ $task->id_task }}">

    {{-- Accent border left drawn as an absolute element to prevent curved border issues --}}
    <div class="status-border" style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: {{ $borderColor }}; z-index: 10;"></div>

    <div>
        {{-- Header --}}
        <div class="flex justify-between items-center mb-3" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="font-bold text-[15px] leading-snug flex-1 pr-3 task-title"
                style="color: {{ $task->status_task === 'DONE' ? '#9ca3af' : '#2d1e17' }};
                       {{ $task->status_task === 'DONE' ? 'text-decoration: line-through;' : '' }}
                       font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                {{ $task->judul_task }}
            </h3>
            <div class="flex gap-2 shrink-0 items-center" style="display: flex; gap: 8px; align-items: center; flex-shrink: 0;">
                <button type="button" onclick="openEditModal('{{ $task->id_task }}')"
                        class="hover:opacity-75 transition-opacity duration-200"
                        style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background: none; border: none; padding: 0; cursor: pointer;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#b4835f" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                    </svg>
                </button>
                <button type="button" onclick="openDeleteModal('{{ $task->id_task }}')"
                        class="hover:opacity-75 transition-opacity duration-200"
                        style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background: none; border: none; padding: 0; cursor: pointer;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                        <line x1="10" y1="11" x2="10" y2="17" />
                        <line x1="14" y1="11" x2="14" y2="17" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Deskripsi --}}
        @if($task->deskripsi)
            <p class="text-[13px] mb-4 line-clamp-2 leading-relaxed task-desc"
               style="color: {{ $task->status_task === 'DONE' ? '#9ca3af' : '#7c6a5e' }};
                      {{ $task->status_task === 'DONE' ? 'text-decoration: line-through;' : '' }}
                      font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                {{ $task->deskripsi }}
            </p>
        @endif

        {{-- Subtasks --}}
        @if($task->subtasks->count() > 0)
            <div class="mb-4 flex flex-col gap-1.5 p-2.5 rounded-xl"
                 style="background: #faf5f0; border: 1px solid #f0e6d3; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                @foreach($task->subtasks as $subtask)
                    <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 8px;">
                        <form method="POST" action="{{ route('subtasks.toggle', $subtask->id_subtask) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="rounded shrink-0 flex items-center justify-center transition-all"
                                    style="width: 16px; height: 16px; border: 1.5px solid {{ $subtask->is_completed ? '#f97316' : '#bfa38a' }};
                                           background: {{ $subtask->is_completed ? '#f97316' : '#ffffff' }}; display: flex; align-items: center; justify-content: center; padding: 0; cursor: pointer;">
                                @if($subtask->is_completed)
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                @endif
                            </button>
                        </form>
                        <span class="text-[11px]"
                              style="color: {{ $subtask->is_completed ? '#9ca3af' : '#4a270f' }};
                                     font-weight: {{ $subtask->is_completed ? '400' : '600' }};
                                     {{ $subtask->is_completed ? 'text-decoration: line-through;' : '' }}">
                            {{ $subtask->nama_subtask }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="flex items-center justify-between mt-auto pt-1" style="display: flex; justify-content: space-between; align-items: center;">

        {{-- Left: Priority + Overdue badges --}}
        <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">

            {{-- Priority Badge --}}
            <span class="task-priority-badge text-[11px] font-bold tracking-wide"
                  style="background-color: {{ $priorityBg }};
                         color: {{ $priorityColor }};
                         padding: 4px 14px;
                         border-radius: 9999px;
                         line-height: 1;
                         display: inline-block;
                         text-align: center;
                         text-transform: capitalize;
                         font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;"
                  data-priority="{{ strtoupper($task->prioritas) }}">
                {{ ucfirst(strtolower($task->prioritas)) }}
            </span>

            {{-- Overdue Badge --}}
            @if($isOverdue)
            <span style="font-size:11px; font-weight:700; color:#ef4444;
                         border:1.5px solid #ef4444; border-radius:9999px;
                         padding:4px 12px; line-height:1; display:inline-block;
                         font-family:'Plus Jakarta Sans','Inter',sans-serif;">
                Overdue
            </span>
            @endif

        </div>

        {{-- Completed / Deadline --}}
        <div class="due-date-wrapper flex items-center" style="display: flex; align-items: center;">

            {{-- Completed Badge --}}
            <span class="completed-badge text-[12px] font-medium flex items-center gap-1.5"
                  style="color: #9ca3af; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; display: {{ $task->status_task === 'DONE' ? 'flex' : 'none' }}; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                Completed
            </span>

            {{-- Deadline --}}
            @if($task->tanggal_deadline)
            <span class="date-badge text-[12px] font-medium flex items-center gap-1.5"
                  style="color: {{ $isOverdue ? '#ef4444' : '#7c6a5e' }}; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; display: {{ $task->status_task === 'DONE' ? 'none' : 'flex' }}; align-items: center; gap: 6px;">
                @if($isOverdue)
                {{-- Warning / Exclamation icon --}}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                @else
                {{-- Clock icon --}}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c6a5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                @endif
                {{ $task->tanggal_deadline->format('d M Y') }}
            </span>
            @endif

        </div>
    </div>

</div>