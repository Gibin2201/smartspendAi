<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Tell Laravel to look for the exact table name 'User'
    protected $table = 'User';

    // 2. Tell Laravel your custom primary key is 'userid'
    protected $primaryKey = 'userid';

    // 3. Explicitly declare which fields can be filled by the registration form
    protected $fillable = [
        'name', 
        'email', 
        'contact', 
        'username', 
        'password'
    ];

    // 4. Hide sensitive fields
    protected $hidden = [
        'password', 
        'remember_token'
    ];

    // 5. Define default attributes for new records
    protected $attributes = [
        'role' => 'user',
        'income' => 0.00,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'income' => 'decimal:2', 
        ];
    }
}