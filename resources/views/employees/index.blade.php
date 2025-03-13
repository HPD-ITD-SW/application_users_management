<x-layout>
    <x-slot name="title">Employees</x-slot>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900">Employee Directory</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Side Panel: Search Filters -->
                <div class="lg:w-1/4 bg-white shadow rounded-lg p-6">
                    <form action="" method="GET">
                        <!-- Unified Text Search: Name or Email -->
                        <div class="mb-4">
                            <label for="search" class="block text-sm font-medium text-gray-700">Search (Name or Email)</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Enter name or email">
                        </div>

                        <!-- Department Filter Dropdown with Checkboxes -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <div class="space-y-1 max-h-48 overflow-y-auto">
                                @foreach($departments as $department)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="departments[]" value="{{ $department }}"
                                               id="dept-{{ $department }}"
                                               class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                               @if(is_array(request('departments')) && in_array($department, request('departments'))) checked @endif>
                                        <label for="dept-{{ $department }}" class="ml-2 text-sm text-gray-700">
                                            {{ $department }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Status Filter Dropdown with Checkboxes -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="space-y-1 max-h-32 overflow-y-auto">
                                @foreach($statuses as $status)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="statuses[]" value="{{ $status }}"
                                               id="status-{{ $status }}"
                                               class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                               @if(is_array(request('statuses')) && in_array($status, request('statuses'))) checked @endif>
                                        <label for="status-{{ $status }}" class="ml-2 text-sm text-gray-700">
                                            {{ $status }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                            Apply Filters
                        </button>
                    </form>
                </div>

                <!-- Right Side: Employee Table -->
                <div class="lg:w-3/4">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"># of Apps</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($employees as $employee)
                                    <tr class="transition transform hover:scale-105 hover:shadow-lg hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $employee->fname }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $employee->lname }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $employee->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $employee->division }}</div>
                                        </td>                                
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($employee->active === 'Active')
                                                    <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                                @endif
                                                <span class="text-sm text-gray-900">{{ $employee->active }}</span>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap text-center">
                                            <div class="text-sm text-gray-900">1</div>
                                        </td>    
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <a href="{{ route('employees.show', $employee->employee_id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        </td>                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
