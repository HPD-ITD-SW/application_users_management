<?php

namespace App\Http\Controllers;

use App\Models\Application;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with('applicationType')
            ->withCount(['employeeApplications' => function ($query) {
                $query->where('status', 'Active')->whereNull('deleted_at');
            }])
            ->get();

        return view('applications.index', compact('applications'));
    }

    public function show($id)
    {
        // Fetch application details and active pivot entries
        $application = Application::with('applicationType')
            ->with(['employeeApplications' => function ($query) {
                $query->where('status', 'Active')->whereNull('deleted_at');
            }])
            ->withCount(['employeeApplications' => function ($query) {
                $query->where('status', 'Active')->whereNull('deleted_at');
            }])
            ->findOrFail($id);

        // Get all employee IDs associated with this application clearly
        $employeeIds = $application->employeeApplications->pluck('employee_id')->toArray();

        // Fetch employee details from the separate employee view-only database
        $employees = \App\Models\Employee::whereIn('employee_id', $employeeApplicationsIds = $employeeIds)->get()->keyBy('employee_id');

        // Combine pivot data with employee details
        $employeeDetails = $application->employeeApplications->map(function ($pivot) use ($employees) {
            $employee = $employees[$pivot->employee_id] ?? null;
            return [
                'employee_id' => $pivot->employee_id,
                'fname' => optional($employee)->fname ?? 'N/A',
                'lname' => optional($employee)->lname,
                'email' => optional($employee)->email,
                'department' => $employee->division ?? 'N/A',
                'hr_status' => $employee->active ?? 'N/A',
                'app_status' => $pivot->status,
            ];
        });

        return view('applications.show', compact('application', 'employeeDetails'));
    }
}

