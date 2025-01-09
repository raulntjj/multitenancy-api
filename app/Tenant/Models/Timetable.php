<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\Traits\HasUserActions;

class Timetable extends Model {
    use HasUserActions;

    protected $connection = 'tenant';
    protected $table = 'timetables';

    protected $fillable = [
        'classroom_id',
        'schedules_id',
        'user_id',
        'discipline_id',
    ];
}
