<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'tenant';
    protected $table = 'users';

    protected $fillable = [
        'name',
        'user',
        'email',
        'password',
    ];

    protected $hidden = ['password'];
}
