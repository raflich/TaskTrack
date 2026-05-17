<?php

use App\Http\Controllers\KanbanController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\ListViewController;
use Illuminate\Support\Facades\Route;


// Redirect root ke kanban
Route::get('/', function () {
    return redirect()->route('kanban.index');
});

// Route auth (dari Breeze)
require __DIR__.'/auth.php';

// Route yang butuh login
Route::middleware('auth')->group(function () {

    // Kanban Board
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');

    // Task
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{id}/move', [TaskController::class, 'moveStatus'])->name('tasks.move');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Subtask
    Route::patch('/subtasks/{id}/toggle', [SubtaskController::class, 'toggle'])->name('subtasks.toggle');
    Route::delete('/subtasks/{id}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');

    // Trash
    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::patch('/trash/{id}/restore', [TrashController::class, 'restore'])->name('trash.restore');
    Route::delete('/trash/{id}', [TrashController::class, 'hardDelete'])->name('trash.hardDelete');
    Route::delete('/trash', [TrashController::class, 'emptyTrash'])->name('trash.empty');

    // List View
Route::get('/list', [ListViewController::class, 'index'])->name('listview.index');

});