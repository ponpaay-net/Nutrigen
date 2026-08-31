<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the Kader profile associated with the user.
     */
    public function kader(): HasOne
    {
        return $this->hasOne(Kader::class);
    }

    /**
     * Get the Puskesmas profile associated with the user.
     */
    public function puskesmas(): HasOne
    {
        return $this->hasOne(Puskesmas::class);
    }

    /**
     * Get the OrangTua profile associated with the user.
     */
    public function orangTua(): HasOne
    {
        return $this->hasOne(OrangTua::class);
    }
}
