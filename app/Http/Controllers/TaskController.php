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

        // Simpan subtasks jika ada, dengan urutan sesuai posisi input
        if ($request->has('subtasks')) {
            $urutan = 1;
            foreach ($request->subtasks as $nama) {
                if (!empty(trim($nama))) {
                    Subtask::create([
                        'id_task'      => $task->id_task,
                        'nama_subtask' => trim($nama),
                        'urutan'       => $urutan++,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Task successfully created!');
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

        // Sync subtasks: preserve is_completed for existing ones, assign urutan
        $incomingIds   = collect($request->input('subtask_ids', []))->filter()->values();
        $incomingNames = collect($request->input('subtask_names', []))->values();

        // Delete subtasks that are no longer in the list
        if ($incomingIds->isNotEmpty()) {
            $task->subtasks()->whereNotIn('id_subtask', $incomingIds->toArray())->delete();
        } else {
            $task->subtasks()->delete();
        }

        // Update existing subtasks: name + assign correct urutan (1-based position)
        $urutan = 1;
        foreach ($incomingIds as $index => $subtaskId) {
            $nama = $incomingNames->get($index, '');
            if (!empty(trim($nama))) {
                Subtask::where('id_subtask', $subtaskId)
                       ->where('id_task', $task->id_task)
                       ->update([
                           'nama_subtask' => trim($nama),
                           'urutan'       => $urutan++,
                       ]);
            }
        }

        // Add brand-new subtasks — continue urutan from where existing left off
        $newNames = collect($request->input('new_subtasks', []));
        foreach ($newNames as $nama) {
            if (!empty(trim($nama))) {
                Subtask::create([
                    'id_task'      => $task->id_task,
                    'nama_subtask' => trim($nama),
                    'urutan'       => $urutan++,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Task successfully updated!');
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

    return redirect()->back()->with('success', 'Status successfully updated!');
    }

    // Soft delete task
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'is_deleted' => 1,
            'deleted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Task successfully moved to Trash!');
    }
}