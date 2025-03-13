<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeView extends Model
{
    // Use the second connection (viewEmployeesOnly)
    protected $connection = 'viewEmployeesOnly';

    // Specify the view name as it appears in your database
    protected $table = 'vw_active_employees'; // Change this to the actual view name

    // Views are typically read-only, so disable timestamps
    public $timestamps = false;

    // If your view doesn’t have a primary key, you can set these:
    protected $primaryKey = 'employee_id'; // Ensure your view has a unique column called "id"
    public $incrementing = false;
}