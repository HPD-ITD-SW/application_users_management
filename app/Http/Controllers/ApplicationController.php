<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
            // Load all records that haven't been deleted
            $query->whereNull('deleted_at');
        }])
        ->withCount(['employeeApplications' => function ($query) {
            // Only count the active ones
            $query->where('status', 'Active')
                ->whereNull('deleted_at');
        }])
        ->findOrFail($id);

    // Get all employee IDs associated with this application clearly
    $employeeIds = $application->employeeApplications->pluck('employee_id')->toArray();

    // Fetch employee details from the separate employee view-only database
    $employees = \App\Models\Employee::whereIn('employee_id', $employeeIds)
                    ->get()->keyBy('employee_id');

    // Combine pivot data with employee details
    $employeeDetails = $application->employeeApplications->map(function ($pivot) use ($employees) {
        $employee = $employees[$pivot->employee_id] ?? null;
        return [
            'employee_id' => $pivot->employee_id,
            'fname'       => optional($employee)->fname ?? 'N/A',
            'lname'       => optional($employee)->lname,
            'email'       => optional($employee)->email,
            'department'  => $employee->division ?? 'N/A',
            'hr_status'   => $employee->active ?? 'N/A',
            'app_status'  => $pivot->status,
        ];
    });

    // Apply sorting logic if requested
    if (request()->has('sort_by')) {
        $sortBy = request('sort_by');
        $order  = request('order', 'asc');

        if ($order === 'desc') {
            $employeeDetails = $employeeDetails->sortByDesc(function ($item) use ($sortBy) {
                return $item[$sortBy] ?? '';
            });
        } else {
            $employeeDetails = $employeeDetails->sortBy(function ($item) use ($sortBy) {
                return $item[$sortBy] ?? '';
            });
        }
        $employeeDetails = $employeeDetails->values();
    }

    // Manually paginate the collection
    $page = Paginator::resolveCurrentPage();
    $perPage = 15;
    $paginatedEmployeeDetails = new LengthAwarePaginator(
        $employeeDetails->forPage($page, $perPage),
        $employeeDetails->count(),
        $perPage,
        $page,
        [
            'path'  => Paginator::resolveCurrentPath(),
            'query' => request()->query() // Ensure sort parameters are appended
        ]
    );

    return view('applications.show', [
        'application'     => $application,
        'employeeDetails' => $paginatedEmployeeDetails,
    ]);
}
}