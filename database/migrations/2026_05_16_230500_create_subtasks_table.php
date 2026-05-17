<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->uuid('id_subtask')->primary();
            $table->uuid('id_task');
            $table->string('nama_subtask', 255);
            $table->tinyInteger('is_completed')->default(0);
            $table->timestamps();

            $table->foreign('id_task')
                  ->references('id_task')
                  ->on('tasks')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtasks');
    }
};