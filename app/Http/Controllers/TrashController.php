<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller
{
    public function index()
    {
        $board = Auth::user()->board;

        // Otomatis hapus permanen task di trash yang sudah lebih dari 7 hari
        $board->trashedTasks()
              ->where('deleted_at', '<=', now()->subDays(7))
              ->delete();

        $trashedTasks = $board->trashedTasks()->with('subtasks')->get();

        return view('trash.index', compact('trashedTasks'));
    }

    public function restore($id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'is_deleted' => 0,
            'deleted_at' => null,
        ]);

        return redirect()->route('trash.index')->with('success', 'Task berhasil direstore!');
    }

    public function hardDelete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('trash.index')->with('success', 'Task berhasil dihapus permanen!');
    }

    public function emptyTrash()
    {
        $board = Auth::user()->board;
        $board->trashedTasks()->each(function ($task) {
            $task->delete();
        });

        return redirect()->route('trash.index')->with('success', 'Trash berhasil dikosongkan!');
    }
}