<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListViewController extends Controller
{
    public function index(Request $request)
    {
        $board = Auth::user()->board;

        $query = $board->tasks()->with('subtasks');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status_task', strtoupper($request->status));
        }

        // Sort
        $sort = $request->get('sort', 'due_date');
        if ($sort === 'due_date') {
            $query->orderBy('tanggal_deadline', 'asc');
        } elseif ($sort === 'priority') {
            $query->orderByRaw("FIELD(prioritas, 'HIGH', 'MEDIUM', 'LOW')");
        } elseif ($sort === 'title') {
            $query->orderBy('judul_task', 'asc');
        }

        $tasks = $query->paginate(10);

        return view('listview.index', compact('tasks'));
    }
}