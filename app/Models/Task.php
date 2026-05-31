<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    protected $primaryKey = 'id_task';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'id_task',
        'id_board',
        'judul_task',
        'deskripsi',
        'status_task',
        'prioritas',
        'tanggal_deadline',
        'is_deleted',
        'deleted_at',
    ];

    protected $casts = [
        'tanggal_deadline' => 'date',
        'deleted_at'       => 'datetime',
        'is_deleted'       => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_task = (string) Str::uuid();
        });
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class, 'id_task', 'id_task')
                    ->orderBy('urutan', 'asc');
    }

    public function board()
    {
        return $this->belongsTo(Board::class, 'id_board', 'id_board');
    }

    // Cek apakah deadline sudah lewat
    public function isOverdue(): bool
    {
        return $this->tanggal_deadline &&
               $this->tanggal_deadline->isPast() &&
               $this->status_task !== 'DONE';
    }

    // Label warna prioritas untuk blade
    public function getPrioritasColorAttribute(): string
    {
        return match($this->prioritas) {
            'HIGH'   => 'bg-red-500',
            'MEDIUM' => 'bg-yellow-500',
            'LOW'    => 'bg-blue-400',
            default  => 'bg-gray-400',
        };
    }
}