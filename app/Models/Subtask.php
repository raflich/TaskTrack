<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subtask extends Model
{
    protected $primaryKey = 'id_subtask';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'id_subtask',
        'id_task',
        'nama_subtask',
        'urutan',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_subtask = (string) Str::uuid();
        });
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'id_task', 'id_task');
    }
}