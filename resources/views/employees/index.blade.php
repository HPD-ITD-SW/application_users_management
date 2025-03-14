<x-layout>
    <x-slot name="title">Employees</x-slot>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900">Employee Directory</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Side Panel: Search Filters and Selected Employees -->
                <div class="lg:w-1/3 bg-white shadow rounded-lg p-6">
                    <form action="{{ route('employees.index') }}" method="GET">
                        <!-- Unified Text Search: Name or Email -->
                        <div class="mb-4">
                            <label for="search" class="block text-sm font-medium text-gray-700">Search (Name or
                                Email)</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Enter name or email">
                        </div>

                        <!-- Department Filter -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <div class="space-y-1 max-h-48 overflow-y-auto">
                                @foreach ($departments as $department)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="departments[]" value="{{ $department }}"
                                            id="dept-{{ $department }}"
                                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                            @if (is_array(request('departments')) && in_array($department, request('departments'))) checked @endif>
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
                                @foreach ($statuses as $status)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="statuses[]" value="{{ $status }}"
                                            id="status-{{ $status }}"
                                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                            @if (is_array(request('statuses')) && in_array($status, request('statuses'))) checked @endif>
                                        <label for="status-{{ $status }}" class="ml-2 text-sm text-gray-700">
                                            {{ $status }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                            Apply Filters
                        </button>
                    </form>

                    <!-- Selected Employees Drop Zone -->
                    <div id="selected-employees-container"
                        class="mt-6 border-2 border-dashed border-gray-300 rounded p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">
                                Selected Employees
                            </h3>
                            <button id="clear-selected" title="Clear Selected Employees"
                                class="text-red-500 hover:text-red-700">
                                <!-- Inline Trashcan Icon (using an SVG) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v1H9V4a1 1 0 011-1z" />
                                </svg>
                            </button>
                        </div>
                        <!-- Scrollable container: max-height triggers scrolling after ~6 rows -->
                        <div class="max-h-60 overflow-y-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            First Name</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Last Name</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email</th>
                                    </tr>
                                </thead>
                                <tbody id="selected-employees-tbody">
                                    
                                    <!-- Server-populated selected employees will be loaded here -->
                                    @if (session('selectedEmployees') && count(session('selectedEmployees')) > 0)
                                        @foreach (session('selectedEmployees') as $emp)
                                            <tr data-email="{{ $emp['email'] }}">
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $emp['fname'] }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $emp['lname'] }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $emp['email'] }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="placeholder-row">
                                            <td colspan="3"
                                                class="px-4 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                                                Drag and drop employees here
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- Button to navigate to the manage-selected page -->
                        <a href="{{ route('employees.selected') }}"
                            class="mt-4 block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                            Manage Selected Employees
                        </a>
                    </div>
                </div>

                <!-- Right Side: Employee Table -->
                <div class="w-full">
                    <div class="bg-white shadow rounded-lg">
                        <table id="employees-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        First Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Last Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        HR Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        # of Apps</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($employees as $employee)
                                    <tr draggable="false"
                                        class="employee-row transition transform hover:scale-105 hover:shadow-lg hover:bg-gray-50"
                                        data-employee-id="{{ $employee->employee_id }}"> <!-- Add this data attribute -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 employee-fname">
                                                {{ $employee->fname }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 employee-lname">
                                                {{ $employee->lname }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 employee-email">
                                                {{ $employee->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 employee-division">
                                                {{ $employee->division }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center employee-active">
                                                @if ($employee->active === 'Active')
                                                    <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                                @endif
                                                <span class="text-sm text-gray-900 employee-status-text">{{ $employee->active }}</span>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap text-center">
                                            <div class="text-sm text-gray-900">{{ $employee->active_applications_count }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <a href="{{ route('employees.show', $employee->employee_id) }}"
                                               class="text-indigo-600 hover:text-indigo-900">View</a>
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

    <!-- Inline JavaScript for Drag & Drop with Session Persistence -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeRows = document.querySelectorAll('.employee-row');
            const selectedEmployeesTbody = document.getElementById('selected-employees-tbody');
            const clearButton = document.getElementById('clear-selected');

            let selectedEmployees = {!! json_encode(session('selectedEmployees') ?? []) !!};

            function updateDropZone() {
                selectedEmployeesTbody.innerHTML = '';
                if (selectedEmployees.length === 0) {
                    selectedEmployeesTbody.innerHTML = `
                <tr id="placeholder-row">
                    <td colspan="3" class="px-4 py-2 whitespace-nowrap text-center text-sm text-gray-500">
                        Click on employees to select them
                    </td>
                </tr>`;
                } else {
                    selectedEmployees.forEach(emp => {
                        const newRow = document.createElement("tr");
                        newRow.setAttribute("data-email", emp.email);
                        newRow.innerHTML = `
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${emp.fname}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${emp.lname}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${emp.email}</td>
                `;
                        selectedEmployeesTbody.appendChild(newRow);
                    });
                }

                highlightSelectedRows();
            }

            function highlightSelectedRows() {
                employeeRows.forEach(row => {
                    const email = row.querySelector('.employee-email').textContent.trim();
                    if (selectedEmployees.some(emp => emp.email === email)) {
                        row.classList.add('bg-indigo-100');
                    } else {
                        row.classList.remove('bg-indigo-100');
                    }
                });
            }

            function updateSession() {
                fetch("{{ route('employees.updateSelected') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        credentials: "same-origin",
                        body: JSON.stringify({
                            selectedEmployees
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Session updated:", data);
                    })
                    .catch(err => console.error(err));
            }

            employeeRows.forEach(row => {
                row.addEventListener('click', function() {
                    if (event.target.closest('a')) {
                        return;
                    }
                    const employeeData = {
                        fname: row.querySelector('.employee-fname').textContent.trim(),
                        lname: row.querySelector('.employee-lname').textContent.trim(),
                        email: row.querySelector('.employee-email').textContent.trim(),
                        division: row.querySelector('.employee-division').textContent.trim(),
                        status: row.querySelector('.employee-status-text').textContent.trim(),
                        employee_id: row.getAttribute('data-employee-id')
                    };

                    const index = selectedEmployees.findIndex(emp => emp.email === employeeData
                        .email);

                    if (index === -1) {
                        selectedEmployees.push(employeeData);
                    } else {
                        selectedEmployees.splice(index, 1);
                    }

                    updateDropZone();
                    updateSession();
                });
            });

            clearButton.addEventListener('click', function(e) {
                e.preventDefault();
                selectedEmployees = [];
                updateDropZone();
                updateSession();
            });

            updateDropZone();
        });
    </script>

</x-layout>
