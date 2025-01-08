<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model {
    protected $connection = 'tenant';
    protected $table = 'schedules';

    protected $fillable = [
        'time',
        'day',
        'brake',
        'brake_type',
    ];
}
