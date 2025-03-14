<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    public function index(Request $request)
{
    // Validate and sanitize incoming request inputs
    $validated = $request->validate([
        'search'      => 'nullable|string|max:255',
        'departments' => 'nullable|array',
        'departments.*' => 'string|max:255',
        'statuses'    => 'nullable|array',
        'statuses.*'  => 'string|max:255',
    ]);

    $search = trim($validated['search'] ?? '');
    $departments = $validated['departments'] ?? [];
    $statuses = $validated['statuses'] ?? [];

    // Run a separate query (using default connection) for active application counts.
    $activeCounts = DB::table('employee_applications')
        ->select('employee_id', DB::raw("COUNT(*) as active_applications_count"))
        ->where('status', 'Active')
        ->whereNull('deleted_at')
        ->groupBy('employee_id')
        ->pluck('active_applications_count', 'employee_id');

    // Build the active and archived queries using the view connection (without subqueries)
    $active = DB::connection('viewEmployeesOnly')->table('vw_active_employees');
    $archived = DB::connection('viewEmployeesOnly')->table('vw_archived_employees');

    // Apply unified text search on fname, lname, and email if provided
    if ($search) {
        $parts = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        if (count($parts) > 1) {
            $active->where(function ($query) use ($parts, $search) {
                foreach ($parts as $part) {
                    $query->where(function ($q) use ($part) {
                        $q->where('fname', 'like', "%{$part}%")
                          ->orWhere('lname', 'like', "%{$part}%");
                    });
                }
                $query->orWhere('email', 'like', "%{$search}%");
            });
            $archived->where(function ($query) use ($parts, $search) {
                foreach ($parts as $part) {
                    $query->where(function ($q) use ($part) {
                        $q->where('fname', 'like', "%{$part}%")
                          ->orWhere('lname', 'like', "%{$part}%");
                    });
                }
                $query->orWhere('email', 'like', "%{$search}%");
            });
        } else {
            $active->where(function ($query) use ($search) {
                $query->where('fname', 'like', "%{$search}%")
                      ->orWhere('lname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
            $archived->where(function ($query) use ($search) {
                $query->where('fname', 'like', "%{$search}%")
                      ->orWhere('lname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }
    }

    // Apply department filter if provided
    if (!empty($departments)) {
        $active->whereIn('division', $departments);
        $archived->whereIn('division', $departments);
    }

    // Apply status filter if provided
    if (!empty($statuses)) {
        $active->whereIn('active', $statuses);
        $archived->whereIn('active', $statuses);
    }

    // Combine both queries using unionAll
    $employeesQuery = $active->unionAll($archived);

    // Retrieve all results
    $allEmployees = $employeesQuery->get();

    // Merge the active application counts into each employee record.
    $allEmployees->transform(function ($employee) use ($activeCounts) {
        $employee->active_applications_count = $activeCounts[$employee->employee_id] ?? 0;
        return $employee;
    });

    // Manual pagination
    $page = $request->get('page', 1);
    $perPage = 15;
    $paginatedEmployees = new \Illuminate\Pagination\LengthAwarePaginator(
        $allEmployees->forPage($page, $perPage),
        $allEmployees->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    // Fetch unique departments (sorted alphabetically)
    $uniqueDepartments = DB::connection('viewEmployeesOnly')
        ->table('vw_active_employees')
        ->select('division')
        ->distinct()
        ->orderBy('division', 'asc')
        ->pluck('division');

    // Fetch unique statuses by unioning Active and Inactive:
    $activeStatus = DB::connection('viewEmployeesOnly')
        ->table('vw_active_employees')
        ->select(DB::raw("'Active' as active"))
        ->distinct();
    $archivedStatus = DB::connection('viewEmployeesOnly')
        ->table('vw_archived_employees')
        ->select(DB::raw("'Inactive' as active"))
        ->distinct();

    $uniqueStatuses = collect($activeStatus->union($archivedStatus)->get())
        ->pluck('active')
        ->unique()
        ->sort()
        ->values();

    return view('employees.index', [
        'employees'   => $paginatedEmployees,
        'departments' => $uniqueDepartments,
        'statuses'    => $uniqueStatuses,
    ]);
}




public function show($employee_id, Request $request)
{
    // Try to get active employee first
    $employee = \App\Models\Employee::where('employee_id', $employee_id)->first();

    // If not found, check archived employees
    if (!$employee) {
        $employee = \App\Models\ArchivedEmployee::where('employee_id', $employee_id)->first();
    }

    // If still not found, throw a 404
    if (!$employee) {
        abort(404);
    }

    // Now that we have the employee (active or archived),
    // proceed to fetch the employee's applications:
    $query = DB::table('employee_applications')
        ->join('applications', 'employee_applications.application_id', '=', 'applications.id')
        ->join('application_types', 'applications.application_type_id', '=', 'application_types.id')
        ->where('employee_applications.employee_id', $employee->employee_id)
        ->whereNull('employee_applications.deleted_at')
        ->select('applications.*', 'application_types.type_name as type_name', 'employee_applications.status as status');

    if ($request->has('sort_by') && in_array($request->get('sort_by'), ['app_name', 'type_name', 'status'])) {
        $order = $request->get('order', 'asc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($request->get('sort_by'), $order);
    }

    $applications = $query->get();

    return view('employees.show', compact('employee', 'applications'));
}


public function updateSelected(Request $request)
{
    $data = $request->validate([
        'selectedEmployees' => 'present|array',
        'selectedEmployees.*.fname' => 'required|string|max:255',
        'selectedEmployees.*.lname' => 'required|string|max:255',
        'selectedEmployees.*.email' => 'required|string|max:255',
        'selectedEmployees.*.division' => 'string|max:255',
        'selectedEmployees.*.status' => 'string|max:255',
        'selectedEmployees.*.employee_id' => 'required|string|max:255',
    ]);

    // If the selectedEmployees array is empty, clear it from the session.
    if (empty($data['selectedEmployees'])) {
        session()->forget('selectedEmployees');
    } else {
        session(['selectedEmployees' => $data['selectedEmployees']]);
    }

    return response()->json([
        'status' => 'success',
        'selectedEmployees' => session('selectedEmployees') ?? []
    ]);
}

public function selectedData()
{
    $selectedEmployees = session('selectedEmployees') ?? [];
    return response()->json($selectedEmployees);
}

public function selected()
{
    $selectedEmployees = collect(session('selectedEmployees', []));

    // Get employee IDs clearly for querying the pivot table
    $employeeIds = $selectedEmployees->pluck('employee_id')->filter()->toArray();

    // Fetch applications for selected employees
    $employeeApps = DB::table('employee_applications')
        ->join('applications', 'employee_applications.application_id', '=', 'applications.id')
        ->whereIn('employee_applications.employee_id', $employeeIds)
        ->whereNull('employee_applications.deleted_at')
        ->select('employee_applications.*', 'applications.app_name')
        ->get()
        ->groupBy('employee_id');

    return view('employees.selected', compact('selectedEmployees', 'employeeApps'));
}






}