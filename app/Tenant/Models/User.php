<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\Traits\HasUserActions;

class User extends Model {
    use HasUserActions;

    protected $connection = 'tenant';
    protected $table = 'users';

    protected $fillable = [
        'name',
        'surname',
        'user',
        'email',
        'photo_path',
        'password',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = ['password'];

    public function roles() {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role');
    }

    public function permissions() {
        return $this->hasManyThrough(
            Permission::class,
            Role::class,
            'role',
            'role',
            'id',
            'role'
        );
    }

    public function teacher() {
        return $this->hasOne(Teacher::class, 'user_id');
    }
}
