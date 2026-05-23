<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_user';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'id_user',
        'nama_user',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_user = (string) Str::uuid();
        });
    }

    public function board()
    {
        return $this->hasOne(Board::class, 'id_user', 'id_user');
    }

    // Helper: ambil 2 huruf awal untuk avatar
    public function getAvatarAttribute(): string
    {
        $words = explode(' ', $this->nama_user);
        if (count($words) >= 2) {
            return strtoupper($words[0][0] . $words[1][0]);
        }
        return strtoupper(substr($this->nama_user, 0, 2));
    }

    /**
     * Send the password reset notification with a reset URL to the web page.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
