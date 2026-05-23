<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanbanController extends Controller
{
    public function index()
    {
        $board = Auth::user()->board;

        $tasks = $board->tasks()->with('subtasks')->orderBy('created_at', 'asc')->get();

        $todo  = $tasks->where('status_task', 'TODO');
        $doing = $tasks->where('status_task', 'DOING');
        $done  = $tasks->where('status_task', 'DONE');

        return view('kanban.index', compact('board', 'todo', 'doing', 'done'));
    }
}