<?php

namespace Database\Factories;

use App\Models\EmployeeApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeApplicationFactory extends Factory
{
    protected $model = EmployeeApplication::class;

    public function definition()
    {
        // Get a random employee_id from the Employee model (assumes the view-only DB is set in the Employee model)
        $employeeId = \App\Models\Employee::inRandomOrder()->value('employee_id');

        // Get a random application_id from the Application model
        $applicationId = \App\Models\Application::inRandomOrder()->value('id');

        return [
            'employee_id'    => $employeeId,
            'application_id' => $applicationId,
            // 80% chance of being NULL; otherwise, a random datetime from the last year.
            'deleted_at'     => $this->faker->boolean(80) ? null : $this->faker->dateTimeBetween('-1 year', 'now'),
            // Randomly choose between "ACTIVE" and "REVOKED"
            'status'         => $this->faker->randomElement(['ACTIVE', 'REVOKED']),
        ];
    }
}
