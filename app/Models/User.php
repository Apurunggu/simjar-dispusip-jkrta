<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'cabang_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function hasRole($name)
    {
        return $this->role?->name === $name;
    }

    public function hasAnyRole($names)
    {
        if (is_string($names)) {
            $names = [$names];
        }
        return $this->role && in_array($this->role->name, $names);
    }
}
