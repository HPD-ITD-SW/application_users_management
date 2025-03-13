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
        'search' => 'nullable|string|max:255',
        'departments' => 'nullable|array',
        'departments.*' => 'string|max:255',
        'statuses' => 'nullable|array',
        'statuses.*' => 'string|max:255',
    ]);

    // Retrieve sanitized filter inputs
    $search = trim($validated['search'] ?? '');
    $departments = $validated['departments'] ?? [];
    $statuses = $validated['statuses'] ?? [];

    // Build the active and archived queries using the query builder
    $active = DB::connection('viewEmployeesOnly')->table('vw_active_employees');
    $archived = DB::connection('viewEmployeesOnly')->table('vw_archived_employees');

    // Apply unified text search on fname, lname, and email if provided
    if ($search) {
        // Split the search string into parts based on whitespace
        $parts = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        if (count($parts) > 1) {
            // For multiple parts, ensure that each part is found in either fname or lname,
            // or that the full search string appears in the email.
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
            // Single term: check fname, lname, or email for a match
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

    // Retrieve all results (assuming the dataset is manageable)
    $allEmployees = $employeesQuery->get();

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

    // Since union() does not support orderBy easily, convert to a collection and sort:
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



public function show($employee_id)
{
    $employee = Employee::where('employee_id', $employeeId = $employee_id = $employee_id = $employee_id)->firstOrFail();

    return view('employees.show', compact('employee'));
}


public function updateSelected(Request $request)
{
    $data = $request->validate([
        'selectedEmployees' => 'present|array',
        'selectedEmployees.*.fname' => 'required|string|max:255',
        'selectedEmployees.*.lname' => 'required|string|max:255',
        'selectedEmployees.*.email' => 'required|string|max:255', // or 'required|email|max:255' if you need valid emails
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




}