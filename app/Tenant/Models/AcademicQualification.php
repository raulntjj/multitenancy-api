<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\Traits\HasUserActions;

class AcademicQualification extends Model {
    use HasUserActions;

    protected $connection = 'tenant';
    protected $table = 'academic_qualifications';

    protected $fillable = [
        'user_id',
        'name',
        'degree',
    ];

    public function teachers() {
        return $this->belongsTo(Teacher::class, 'user_id');
    }
}
