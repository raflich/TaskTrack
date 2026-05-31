<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->unsignedInteger('urutan')->default(0)->after('nama_subtask');
        });

        // Set urutan for existing subtasks based on their created_at order per task
        $tasks = DB::table('subtasks')
            ->select('id_task')
            ->distinct()
            ->get();

        foreach ($tasks as $task) {
            $subtasks = DB::table('subtasks')
                ->where('id_task', $task->id_task)
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($subtasks as $index => $subtask) {
                DB::table('subtasks')
                    ->where('id_subtask', $subtask->id_subtask)
                    ->update(['urutan' => $index + 1]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
};
