<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model {
    protected $connection = 'tenant';
    protected $table = 'timetables';

    protected $fillable = [
        'classroom_id',
        'schedules_id',
        'user_id',
        'discipline_id',
    ];
}
