<x-layout>
    <x-slot name="title">User Details</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Employee Details: {{ $employee->fname }} {{ $employee->lname }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Employee Details Card -->
        <x-simple-table :title="$employee->fname . ' ' . $employee->lname" description="Detailed employee information." :rows="[
            ['label' => 'First Name', 'value' => $employee->fname],
            ['label' => 'Last Name', 'value' => $employee->lname],
            ['label' => 'Department', 'value' => $employee->division],
            ['label' => 'HR Status', 'value' => $employee->active],
            ['label' => 'Email Address', 'value' => $employee->email],
        ]" />

@php
    $columns = [
        ['label' => 'Application Name', 'key' => 'app_name', 'sortable' => true],
        ['label' => 'Type',             'key' => 'type_name', 'sortable' => true],
        ['label' => 'Status',           'key' => 'status', 'sortable' => true],
        ['label' => 'Actions',          'key' => 'actions', 'sortable' => false],
    ];

    $employeeApplications = $applications->map(function ($app) {
        return [
            'app_name'  => $app->app_name,
            'type_name' => $app->type_name,
            'status'    => strtolower(trim($app->status)) === 'active'
                ? new \Illuminate\Support\HtmlString(
                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">'
                    . $app->status .
                    '</span>'
                  )
                : new \Illuminate\Support\HtmlString(
                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">'
                    . $app->status .
                    '</span>'
                  ),
            'actions'   => new \Illuminate\Support\HtmlString(
                    '<a href="' . route('applications.show', $app->id) . '" class="text-indigo-600 hover:text-indigo-900">View</a>'
                  ),
        ];
    })->toArray();
@endphp

<x-sortable-table 
    title="Associated Applications"
    description="Applications in which this employee is involved."
    :columns="$columns"
    :tableData="$employeeApplications" 
/>
</x-layout>
