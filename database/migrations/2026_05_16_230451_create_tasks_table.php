<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id_task')->primary();
            $table->uuid('id_board');
            $table->string('judul_task', 255);
            $table->text('deskripsi')->nullable();
            $table->enum('status_task', ['TODO', 'DOING', 'DONE'])->default('TODO');
            $table->enum('prioritas', ['LOW', 'MEDIUM', 'HIGH'])->default('MEDIUM');
            $table->date('tanggal_deadline')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('id_board')
                  ->references('id_board')
                  ->on('boards')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};