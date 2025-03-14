<x-layout>
    <x-slot name="title">{{ $application->app_name }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $application->app_name }}
        </h2>
    </x-slot>

    <x-simple-table 
    :title="$application->app_name"
    :description="$application->description"
    :rows="[
        ['label' => 'URL', 'value' => view('components.link', ['url' => $application->url])->render()],
        ['label' => 'Type', 'value' => e($application->applicationType->type_name)], 
        ['label' => 'Active Users', 'value' => e($application->employee_applications_count)],
    ]"
/>

@php
    $employeeColumns = [
        ['label' => 'First Name', 'key' => 'fname', 'sortable' => true],
        ['label' => 'Last Name',  'key' => 'lname', 'sortable' => true],
        ['label' => 'Email',      'key' => 'email', 'sortable' => true],
        ['label' => 'Department', 'key' => 'department', 'sortable' => true],
        ['label' => 'HR Status',  'key' => 'hr_status', 'sortable' => true],
        ['label' => 'App Status', 'key' => 'app_status', 'sortable' => true],
        ['label' => 'Actions',    'key' => 'actions', 'sortable' => false],
    ];

    $employeeTableData = $employeeDetails->map(function ($employee) {
        return [
            'fname'       => $employee['fname'],
            'lname'       => $employee['lname'],
            'email'       => $employee['email'],
            'department'  => $employee['department'],
            // HR Status with green dot if Active
            'hr_status'   => new \Illuminate\Support\HtmlString(
                ($employee['hr_status'] === 'Active'
                    ? '<span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>'
                    : ''
                ) .
                '<span class="text-sm text-gray-900 employee-status-text">' . $employee['hr_status'] . '</span>'
            ),
            // App Status formatted similar to employeeApplications
            'app_status'  => strtolower(trim($employee['app_status'])) === 'active'
                ? new \Illuminate\Support\HtmlString(
                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">'
                    . $employee['app_status'] .
                    '</span>'
                  )
                : new \Illuminate\Support\HtmlString(
                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">'
                    . $employee['app_status'] .
                    '</span>'
                  ),
            // Actions: for example a "View" link
            'actions'     => new \Illuminate\Support\HtmlString(
                '<a href="' . route('employees.show', $employee['employee_id']) . '" class="text-indigo-600 hover:text-indigo-900">View</a>'
            ),
        ];
    })->toArray();
@endphp


<x-sortable-table 
    title="Employee Details"
    description="List of employees for this application."
    :columns="$employeeColumns"
    :tableData="$employeeTableData"
/>
<!-- Pagination links -->
<div class="mt-4">
    {{ $employeeDetails->links() }}
</div>
    </div>
</x-layout>
