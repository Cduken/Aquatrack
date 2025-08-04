<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class Management extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = 'managements';
    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'position',
        'department',
        'contact_number',
        'notes',
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
}


