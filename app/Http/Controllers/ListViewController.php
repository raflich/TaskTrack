<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListViewController extends Controller
{
    public function index(Request $request)
    {
        $board = Auth::user()->board;

        // Query langsung dari Task, bukan dari relasi board
        // supaya sorting tidak di-override relasi
        $query = Task::with('subtasks')
                     ->where('id_board', $board->id_board)
                     ->where('is_deleted', 0);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status_task', strtoupper($request->status));
        }

        // Sort
        $sort = $request->get('sort', 'due_date');

        if ($sort === 'due_date') {
            $query->orderByRaw('ISNULL(tanggal_deadline) ASC')
                  ->orderBy('tanggal_deadline', 'asc');

        } elseif ($sort === 'priority') {
            $query->orderByRaw("FIELD(prioritas, 'HIGH', 'MEDIUM', 'LOW')");

        } elseif ($sort === 'title') {
            $query->orderBy('judul_task', 'asc');
        }

        $tasks = $query->paginate(10)->withQueryString();

        return view('listview.index', compact('tasks'));
    }
}