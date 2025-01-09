<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\Traits\HasUserActions;

class Role extends Model {
    use HasUserActions;

    protected $connection = 'tenant';
    protected $table = 'roles';

    protected $fillable = [
        'role',
    ];

    public function permissions() {
        return $this->hasMany(Permission::class, 'role_permissions', 'role', 'permission');
    }

    public function users() {
        return $this->hasMany(User::class, 'user_roles', 'user_id', 'role');
    }
}
