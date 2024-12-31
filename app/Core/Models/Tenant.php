<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model {
    protected $table = 'tenants';

    protected $fillable = [
        'name',
        'slug',
        'db_host',
        'db_name',
        'db_user',
        'db_password',
    ];

        /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'db_password',
    ];
}