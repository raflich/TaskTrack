<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Tampil form edit
    public function edit($id)
    {
        $task = Task::with('subtasks')->findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    // Simpan task baru
    public function store(Request $request)
    {
        $request->validate([
            'judul_task'       => ['required', 'string', 'max:255'],
            'prioritas'        => ['required', 'in:LOW,MEDIUM,HIGH'],
            'tanggal_deadline' => ['nullable', 'date'],
            'deskripsi'        => ['nullable', 'string'],
        ]);

        $board = Auth::user()->board;

        $task = Task::create([
            'id_board'         => $board->id_board,
            'judul_task'       => $request->judul_task,
            'deskripsi'        => $request->deskripsi,
            'status_task'      => 'TODO',
            'prioritas'        => $request->prioritas,
            'tanggal_deadline' => $request->tanggal_deadline,
        ]);

        // Simpan subtasks jika ada
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $nama) {
                if (!empty(trim($nama))) {
                    Subtask::create([
                        'id_task'      => $task->id_task,
                        'nama_subtask' => trim($nama),
                    ]);
                }
            }
        }

        return redirect()->route('kanban.index')->with('success', 'Task berhasil dibuat!');
    }

    // Update task
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_task'       => ['required', 'string', 'max:255'],
            'prioritas'        => ['required', 'in:LOW,MEDIUM,HIGH'],
            'tanggal_deadline' => ['nullable', 'date'],
            'deskripsi'        => ['nullable', 'string'],
        ]);

        $task = Task::findOrFail($id);

        $task->update([
            'judul_task'       => $request->judul_task,
            'deskripsi'        => $request->deskripsi,
            'prioritas'        => $request->prioritas,
            'tanggal_deadline' => $request->tanggal_deadline,
        ]);

        // Hapus subtask lama, simpan yang baru
        $task->subtasks()->delete();

        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $nama) {
                if (!empty(trim($nama))) {
                    Subtask::create([
                        'id_task'      => $task->id_task,
                        'nama_subtask' => trim($nama),
                    ]);
                }
            }
        }

        return redirect()->route('kanban.index')->with('success', 'Task berhasil diperbarui!');
    }

    // Pindah status task
    public function moveStatus(Request $request, $id)
    {
    $request->validate([
        'status_task' => ['required', 'in:TODO,DOING,DONE'],
    ]);

    $task = Task::findOrFail($id);
    $task->update(['status_task' => $request->status_task]);

    // Bisa terima JSON (drag drop) maupun form biasa
    if ($request->expectsJson() || $request->wantsJson()) {
        return response()->json(['success' => true]);
    }

    return redirect()->route('kanban.index')->with('success', 'Status diperbarui!');
    }

    // Soft delete task
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'is_deleted' => 1,
            'deleted_at' => now(),
        ]);

        return redirect()->route('kanban.index')->with('success', 'Task dipindahkan ke trash!');
    }
}