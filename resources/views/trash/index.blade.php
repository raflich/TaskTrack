@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 style="font-size: 28px; font-weight: 800; color: #2d1e17; margin: 0 0 4px; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">Trash</h1>
    <p style="font-size: 14px; color: #a08878; margin: 0 0 24px; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
        Tasks here will be permanently deleted after 7 days.
    </p>

    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-3">
        @forelse($trashedTasks as $task)
            <div class="trash-item bg-white rounded-xl px-5 py-4 flex items-center justify-between shadow-sm" style="border: 1.5px solid #eee6da;">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-10 rounded-full shrink-0
                                {{ $task->prioritas === 'HIGH'
                                   ? 'bg-red-500'
                                   : ($task->prioritas === 'MEDIUM' ? 'bg-purple-500' : 'bg-orange-400') }}">
                    </div>
                    <div>
                        <p class="trash-title" style="color: #2d1e17; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; font-size: 15px; font-weight: 600; margin: 0;">{{ $task->judul_task }}</p>
                        <p style="font-size: 12px; color: #a08878; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; display: flex; align-items: center; gap: 4px; margin: 2px 0 0 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px; color: #a08878;" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Deleted {{ $task->deleted_at?->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-2 shrink-0">
                    {{-- Restore --}}
                    <button onclick="openRestoreModal('{{ $task->id_task }}')"
                            class="transition"
                            style="background-color: #f97316; color: #ffffff; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; font-size: 13px; font-weight: 600; padding: 7px 16px; border-radius: 8px; border: none; cursor: pointer;"
                            onmouseover="this.style.backgroundColor='#ea580c'"
                            onmouseout="this.style.backgroundColor='#f97316'">
                        Restore
                    </button>
                    {{-- Hard Delete --}}
                    <button onclick="openHardDeleteModal('{{ $task->id_task }}')"
                            class="transition"
                            style="background-color: #ef4444; color: #ffffff; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; font-size: 13px; font-weight: 600; padding: 7px 16px; border-radius: 8px; border: none; cursor: pointer;"
                            onmouseover="this.style.backgroundColor='#dc2626'"
                            onmouseout="this.style.backgroundColor='#ef4444'">
                        Delete Permanently
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-16" style="color: #bfa38a; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
                <p style="font-size: 14px; margin: 0;">Trash kosong.</p>
            </div>
        @endforelse
    </div>

    {{-- Empty Trash --}}
    @if($trashedTasks->count() > 0)
        <div class="mt-6 flex justify-center">
            <button onclick="openEmptyTrashModal()"
                    class="transition"
                    style="background: none; border: 1.5px solid #ef4444; color: #ef4444; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; font-size: 14px; font-weight: 600; padding: 8px 20px; border-radius: 8px; cursor: pointer;"
                    onmouseover="this.style.backgroundColor='#fef2f2'"
                    onmouseout="this.style.backgroundColor='transparent'">
                Empty Trash Now
            </button>
        </div>
    @endif
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL RESTORE --}}
{{-- ══════════════════════════════════════ --}}
<div id="modalRestore" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-xl text-center" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">

        {{-- Icon --}}
        <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-orange-500" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11
                         11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </div>

        <h2 style="font-size: 18px; font-weight: 700; color: #2d1e17; margin: 0 0 8px; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">Restore Task?</h2>
        <p style="font-size: 14px; color: #8c7462; line-height: 1.5; margin: 0 0 24px; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
            Are you sure you want to restore this task?<br/>
            The task will be moved back to your active tasks.
        </p>

        <form id="restoreForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <button type="submit"
                    style="width: 100%; padding: 10px 0; background-color: #f97316; color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 12px; border: none; cursor: pointer; transition: background-color 0.2s; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; margin-bottom: 12px;"
                    onmouseover="this.style.backgroundColor='#ea580c'"
                    onmouseout="this.style.backgroundColor='#f97316'">
                Restore
            </button>
        </form>

        <button onclick="closeRestoreModal()"
                style="background: none; border: none; color: #8c7462; font-size: 14px; font-weight: 500; cursor: pointer; transition: color 0.2s; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;"
                onmouseover="this.style.color='#5a4a3a'"
                onmouseout="this.style.color='#8c7462'">
            Cancel
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL HARD DELETE --}}
{{-- ══════════════════════════════════════ --}}
<div id="modalHardDelete" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-xl text-center" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">

        {{-- Icon --}}
        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                         L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>

        <h2 style="font-size: 18px; font-weight: 700; color: #2d1e17; margin: 0 0 8px; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">Delete Permanently?</h2>
        <p style="font-size: 14px; color: #8c7462; line-height: 1.5; margin: 0 0 24px; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
            This action cannot be undone. The task<br/>
            will be permanently removed forever.
        </p>

        <form id="hardDeleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <button type="submit"
                    style="width: 100%; padding: 10px 0; background-color: #ef4444; color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 12px; border: none; cursor: pointer; transition: background-color 0.2s; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; margin-bottom: 12px;"
                    onmouseover="this.style.backgroundColor='#dc2626'"
                    onmouseout="this.style.backgroundColor='#ef4444'">
                Delete Permanently
            </button>
        </form>

        <button onclick="closeHardDeleteModal()"
                style="background: none; border: none; color: #8c7462; font-size: 14px; font-weight: 500; cursor: pointer; transition: color 0.2s; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;"
                onmouseover="this.style.color='#5a4a3a'"
                onmouseout="this.style.color='#8c7462'">
            Cancel
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL EMPTY TRASH --}}
{{-- ══════════════════════════════════════ --}}
<div id="modalEmptyTrash" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-xl text-center" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">

        {{-- Icon --}}
        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                         L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>

        <h2 style="font-size: 18px; font-weight: 700; color: #2d1e17; margin: 0 0 8px; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">Empty Trash?</h2>
        <p style="font-size: 14px; color: #8c7462; line-height: 1.5; margin: 0 0 24px; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
            This will permanently delete all tasks<br/>
            in trash. This action cannot be undone.
        </p>

        <form method="POST" action="{{ route('trash.empty') }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                    style="width: 100%; padding: 10px 0; background-color: #ef4444; color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 12px; border: none; cursor: pointer; transition: background-color 0.2s; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; margin-bottom: 12px;"
                    onmouseover="this.style.backgroundColor='#dc2626'"
                    onmouseout="this.style.backgroundColor='#ef4444'">
                Empty Trash Now
            </button>
        </form>

        <button onclick="closeEmptyTrashModal()"
                style="background: none; border: none; color: #8c7462; font-size: 14px; font-weight: 500; cursor: pointer; transition: color 0.2s; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;"
                onmouseover="this.style.color='#5a4a3a'"
                onmouseout="this.style.color='#8c7462'">
            Cancel
        </button>
    </div>
</div>

<script>
// ══ RESTORE ══
function openRestoreModal(taskId) {
    document.getElementById('restoreForm').action = `/trash/${taskId}/restore`;
    document.getElementById('modalRestore').classList.remove('hidden');
}
function closeRestoreModal() {
    document.getElementById('modalRestore').classList.add('hidden');
}

// ══ HARD DELETE ══
function openHardDeleteModal(taskId) {
    document.getElementById('hardDeleteForm').action = `/trash/${taskId}`;
    document.getElementById('modalHardDelete').classList.remove('hidden');
}
function closeHardDeleteModal() {
    document.getElementById('modalHardDelete').classList.add('hidden');
}

// ══ EMPTY TRASH ══
function openEmptyTrashModal() {
    document.getElementById('modalEmptyTrash').classList.remove('hidden');
}
function closeEmptyTrashModal() {
    document.getElementById('modalEmptyTrash').classList.add('hidden');
}

// ══ TUTUP MODAL KLIK LUAR ══
['modalRestore', 'modalHardDelete', 'modalEmptyTrash'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>

@endsection