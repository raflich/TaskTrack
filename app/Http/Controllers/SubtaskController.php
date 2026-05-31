<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function toggle($id)
    {
        $subtask = Subtask::findOrFail($id);
        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_completed' => $subtask->is_completed,
                'message' => 'Subtask updated!'
            ]);
        }

        return redirect()->back()->with('success', 'Subtask updated!');
    }

    public function destroy($id)
    {
        $subtask = Subtask::findOrFail($id);
        $subtask->delete();

        return redirect()->back()->with('success', 'Subtask successfully deleted!');
    }
}