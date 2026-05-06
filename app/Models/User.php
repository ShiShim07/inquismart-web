<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'user_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== USER CODE GENERATION ====================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->user_code)) {
                $count = self::count() + 1;
                $user->user_code = 'USER-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}