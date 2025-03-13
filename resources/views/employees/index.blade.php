<x-layout>
    <x-slot name="title">Employees</x-slot>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900">Employee Directory</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Side Panel: Search Filters and Selected Employees -->
                <div class="lg:w-1/4 bg-white shadow rounded-lg p-6">
                    <form action="{{ route('employees.index') }}" method="GET">
                        <!-- Unified Text Search: Name or Email -->
                        <div class="mb-4">
                            <label for="search" class="block text-sm font-medium text-gray-700">Search (Name or Email)</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Enter name or email">
                        </div>

                        <!-- Department Filter -->
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

                        <!-- Status Filter -->
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

                    <!-- Selected Employees Drop Zone -->
                    <div id="selected-employees-container" class="mt-6 border-2 border-dashed border-gray-300 rounded p-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Selected Employees</h3>
                        <!-- Scrollable container: max-height kicks in after ~6 rows -->
                        <div class="max-h-60 overflow-y-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    </tr>
                                </thead>
                                <tbody id="selected-employees-tbody">
                                    <!-- Dropped employee rows will be added here -->
                                </tbody>
                            </table>
                        </div>
                        <!-- Button to navigate to the manage-selected page -->
                        <a href="{{ route('employees.selected') }}" class="mt-4 block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                            Manage Selected Employees
                        </a>
                    </div>
                </div>

                <!-- Right Side: Employee Table -->
                <div class="lg:w-3/4">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <table id="employees-table" class="min-w-full divide-y divide-gray-200">
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
                                    <tr draggable="true" class="employee-row transition transform hover:scale-105 hover:shadow-lg hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 employee-fname">{{ $employee->fname }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 employee-lname">{{ $employee->lname }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 employee-email">{{ $employee->email }}</div>
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

    <!-- Inline JavaScript for Drag & Drop with Persistence -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log("Drag and drop script loaded.");

            // Utility functions for localStorage persistence
            function loadSelectedEmployees() {
                let data = localStorage.getItem('selectedEmployees');
                try {
                    return data ? JSON.parse(data) : [];
                } catch (e) {
                    return [];
                }
            }

            function saveSelectedEmployees(employees) {
                localStorage.setItem('selectedEmployees', JSON.stringify(employees));
            }

            function updateDropZone() {
                const dropZone = document.getElementById('selected-employees-tbody');
                dropZone.innerHTML = ''; // clear existing rows
                const selectedEmployees = loadSelectedEmployees();
                selectedEmployees.forEach(emp => {
                    let newRow = document.createElement('tr');
                    newRow.setAttribute('data-email', emp.email);
                    newRow.innerHTML = `
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${emp.fname}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${emp.lname}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${emp.email}</td>
                    `;
                    dropZone.appendChild(newRow);
                });
            }

            // Load persisted selected employees on page load
            updateDropZone();

            const dropZoneContainer = document.getElementById('selected-employees-container');
            dropZoneContainer.addEventListener('dragover', function (e) {
                e.preventDefault();
                dropZoneContainer.classList.add('bg-gray-100');
            });
            dropZoneContainer.addEventListener('dragleave', function (e) {
                e.preventDefault();
                dropZoneContainer.classList.remove('bg-gray-100');
            });
            dropZoneContainer.addEventListener('drop', function (e) {
                e.preventDefault();
                dropZoneContainer.classList.remove('bg-gray-100');
                const data = e.dataTransfer.getData("text/plain");
                console.log("Data dropped:", data);
                let employee;
                try {
                    employee = JSON.parse(data);
                } catch (error) {
                    console.error("Error parsing employee data:", error);
                    return;
                }
                let selectedEmployees = loadSelectedEmployees();
                // Check for duplicate entries
                if (selectedEmployees.some(emp => emp.email === employee.email)) return;
                selectedEmployees.push(employee);
                saveSelectedEmployees(selectedEmployees);
                updateDropZone();
            });

            // Attach dragstart event to each employee row
            const rows = document.querySelectorAll('.employee-row');
            rows.forEach(function (row) {
                row.addEventListener('dragstart', function (e) {
                    const employeeData = {
                        fname: row.querySelector('.employee-fname').textContent.trim(),
                        lname: row.querySelector('.employee-lname').textContent.trim(),
                        email: row.querySelector('.employee-email').textContent.trim()
                    };
                    console.log("Dragging employee:", employeeData);
                    e.dataTransfer.setData("text/plain", JSON.stringify(employeeData));
                });
            });
        });
    </script>
</x-layout>
