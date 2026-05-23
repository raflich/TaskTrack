@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-bold text-gray-800 mb-1">Trash</h1>
    <p class="text-sm text-gray-400 mb-6">
        Tasks here will be permanently deleted after 7 days.
    </p>

    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col gap-3">
        @forelse($trashedTasks as $task)
            <div class="trash-item bg-white rounded-xl px-5 py-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-10 rounded-full shrink-0
                                {{ $task->prioritas === 'HIGH'
                                   ? 'bg-red-500'
                                   : ($task->prioritas === 'MEDIUM' ? 'bg-yellow-500' : 'bg-blue-400') }}">
                    </div>
                    <div>
                        <p class="trash-title font-semibold text-gray-800 text-sm">{{ $task->judul_task }}</p>
                        <p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Deleted {{ $task->deleted_at?->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-2 shrink-0">
                    {{-- Restore --}}
                    <button onclick="openRestoreModal('{{ $task->id_task }}')"
                            class="flex items-center gap-1 bg-orange-500 hover:bg-orange-600
                                   text-white text-xs px-3 py-1.5 rounded-lg transition">
                        Restore
                    </button>
                    {{-- Hard Delete --}}
                    <button onclick="openHardDeleteModal('{{ $task->id_task }}')"
                            class="flex items-center gap-1 bg-red-500 hover:bg-red-600
                                   text-white text-xs px-3 py-1.5 rounded-lg transition">
                        Delete Permanently
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-400">
                <p class="text-sm">Trash kosong.</p>
            </div>
        @endforelse
    </div>

    {{-- Empty Trash --}}
    @if($trashedTasks->count() > 0)
        <div class="mt-6 flex justify-center">
            <button onclick="openEmptyTrashModal()"
                    class="flex items-center gap-2 border border-red-400 text-red-500
                           hover:bg-red-50 text-sm px-5 py-2 rounded-lg transition">
                Empty Trash Now
            </button>
        </div>
    @endif
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL RESTORE --}}
{{-- ══════════════════════════════════════ --}}
<div id="modalRestore" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-xl text-center">

        {{-- Icon --}}
        <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-orange-500" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11
                         11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </div>

        <h2 class="text-lg font-bold text-gray-800 mb-2">Restore Task?</h2>
        <p class="text-sm text-gray-400 mb-6">
            Are you sure you want to restore this task?<br/>
            The task will be moved back to your active tasks.
        </p>

        <form id="restoreForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold
                           py-2.5 rounded-xl text-sm transition mb-3">
                Restore
            </button>
        </form>

        <button onclick="closeRestoreModal()"
                class="text-sm text-gray-400 hover:text-gray-600 transition">
            Cancel
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL HARD DELETE --}}
{{-- ══════════════════════════════════════ --}}
<div id="modalHardDelete" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-xl text-center">

        {{-- Icon --}}
        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                         L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>

        <h2 class="text-lg font-bold text-gray-800 mb-2">Delete Permanently?</h2>
        <p class="text-sm text-gray-400 mb-6">
            This action cannot be undone. The task<br/>
            will be permanently removed forever.
        </p>

        <form id="hardDeleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold
                           py-2.5 rounded-xl text-sm transition mb-3">
                Delete Permanently
            </button>
        </form>

        <button onclick="closeHardDeleteModal()"
                class="text-sm text-gray-400 hover:text-gray-600 transition">
            Cancel
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- MODAL EMPTY TRASH --}}
{{-- ══════════════════════════════════════ --}}
<div id="modalEmptyTrash" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-xl text-center">

        {{-- Icon --}}
        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                         L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>

        <h2 class="text-lg font-bold text-gray-800 mb-2">Empty Trash?</h2>
        <p class="text-sm text-gray-400 mb-6">
            This will permanently delete all tasks<br/>
            in trash. This action cannot be undone.
        </p>

        <form method="POST" action="{{ route('trash.empty') }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold
                           py-2.5 rounded-xl text-sm transition mb-3">
                Empty Trash Now
            </button>
        </form>

        <button onclick="closeEmptyTrashModal()"
                class="text-sm text-gray-400 hover:text-gray-600 transition">
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