<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedEmployee extends Model
{
    protected $connection = 'viewEmployeesOnly';
    protected $table = 'vw_archived_employees';

    public $incrementing = false;
    public $timestamps = false;

    protected $primaryKey = 'employee_id';
    protected $keyType = 'string';
}
