<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\Traits\HasUserActions;

class Schedule extends Model {
    use HasUserActions;

    protected $connection = 'tenant';
    protected $table = 'schedules';

    protected $fillable = [
        'time',
        'day',
        'brake',
        'brake_type',
    ];
}
