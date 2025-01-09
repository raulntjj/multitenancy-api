<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\Traits\HasUserActions;

class Permission extends Model {
    use HasUserActions;

    protected $connection = 'tenant';
    protected $table = 'permissions';

    protected $fillable = [
        'permisison',
    ];

    public function role() {
        return $this->hasMany(Role::class, 'role_permissions', 'permission', 'role');
    }
}
