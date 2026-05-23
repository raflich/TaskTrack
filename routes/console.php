<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Task;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Command untuk hapus task di trash yang sudah lebih dari 7 hari
Artisan::command('tasks:prune-trash', function () {
    $count = Task::where('is_deleted', 1)
        ->where('deleted_at', '<=', now()->subDays(7))
        ->delete();

    $this->info("Berhasil menghapus permanen {$count} task yang sudah lebih dari 7 hari di trash.");
})->purpose('Hapus permanen task di trash yang sudah lebih dari 7 hari');

// Schedule command agar jalan otomatis setiap hari
Schedule::command('tasks:prune-trash')->daily();
