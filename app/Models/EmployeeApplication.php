<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeApplication extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'employee_applications';
    public $timestamps = false;


    protected $fillable = [
        'employee_id',
        'application_id',
        'status',
    ];

    protected $dates = ['deleted_at'];
}
