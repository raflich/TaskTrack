<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        $board   = Auth::user()->board;

        if (empty($keyword)) {
            return response()->json([]);
        }

        $tasks = $board->tasks()
                       ->where('judul_task', 'LIKE', "%{$keyword}%")
                       ->limit(8)
                       ->get(['id_task', 'judul_task', 'status_task', 'prioritas', 'tanggal_deadline']);

        return response()->json($tasks);
    }
}