<?php

namespace Database\Factories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'app_name' => $this->faker->company,
            'application_type_id' => DB::table('application_types')->inRandomOrder()->value('id'),
            'url' => $this->faker->url,
            'description' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Application $application) {
            // Explicit employee_id you want to link (replace with your actual ID)
            $employeeId = Employee::where('email', 'b.lindqvist')->first()->employee_id;


            // Insert into the pivot table directly using DB facade for explicit clarity
            DB::table('employee_applications')->insert([
                'employee_id' => $employeeId,
                'application_id' => $application->id,
                'status' => 'Active',
                'deleted_at' => null,
            ]);
        });
    }
}
