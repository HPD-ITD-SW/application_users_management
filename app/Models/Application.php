<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $connection = 'mysql';

    protected $table = 'applications';

    protected $fillable = [
        'app_name',
        'application_type_id',
        'url',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function applicationType(): BelongsTo
    {
        return $this->belongsTo(ApplicationType::class);
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_applications', 'application_id', 'employee_id')
            ->withPivot('status', 'deleted_at')
            ->withTimestamps();
    }

    // Relationship to the pivot table directly (without joining EmployeeView)
    public function employeeApplications()
    {
        return $this->hasMany(EmployeeApplication::class, 'application_id');
    }
}
