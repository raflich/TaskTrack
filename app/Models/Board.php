<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Board extends Model
{
    protected $primaryKey = 'id_board';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'id_board',
        'id_user',
        'nama_board',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_board = (string) Str::uuid();
        });
    }

    // Tasks aktif (belum dihapus)
    public function tasks()
    {
        return $this->hasMany(Task::class, 'id_board', 'id_board')
                    ->where('is_deleted', 0)
                    ->orderBy('created_at', 'asc');
    }

    // Tasks di trash
    public function trashedTasks()
    {
        return $this->hasMany(Task::class, 'id_board', 'id_board')
                    ->where('is_deleted', 1)
                    ->orderBy('deleted_at', 'desc');
    }
}