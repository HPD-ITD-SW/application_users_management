<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeApplication extends Model
{
    use SoftDeletes;

    protected $table = 'employee_applications';

    protected $fillable = [
        'employee_id',
        'application_id',
        'status',
    ];

    protected $dates = ['deleted_at'];
}
