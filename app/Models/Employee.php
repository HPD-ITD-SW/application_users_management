<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    protected $connection = 'viewEmployeesOnly';

    protected $table = 'vw_active_employees';

    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = 'employee_id'; // Assuming 'employee_id' is your view's PK

    protected $keyType = 'string'; // or 'string', depending on your schema

    // Define the relationship explicitly:
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'employee_applications', 'employee_id', 'application_id')
                    ->withPivot('status', 'deleted_at')
                    ->withTimestamps();
    }
}
