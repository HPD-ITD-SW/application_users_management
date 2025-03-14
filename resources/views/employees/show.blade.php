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
        {{-- <!-- Applications Table Card -->
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Associated Applications
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Applications in which this employee is involved.
                </p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- Application Name Header -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a
                                href="{{ request()->fullUrlWithQuery(['sort_by' => 'app_name', 'order' => request('order') === 'asc' && request('sort_by') === 'app_name' ? 'desc' : 'asc']) }}">
                                Application Name
                            </a>
                        </th>
                        <!-- Type Header -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a
                                href="{{ request()->fullUrlWithQuery(['sort_by' => 'type_name', 'order' => request('order') === 'asc' && request('sort_by') === 'type_name' ? 'desc' : 'asc']) }}">
                                Type
                            </a>
                        </th>
                        <!-- Status Header -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a
                                href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'order' => request('order') === 'asc' && request('sort_by') === 'status' ? 'desc' : 'asc']) }}">
                                Status
                            </a>
                        </th>
                        <!-- Actions Column (not sortable) -->
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $app->app_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $app->type_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if (strtolower(trim($app->status)) === 'active')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $app->status }}
                                    </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $app->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('applications.show', $app->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                No applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div> --}}
</x-layout>
