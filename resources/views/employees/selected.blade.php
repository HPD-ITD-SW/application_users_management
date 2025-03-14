<x-layout>
    <x-slot name="title">Manage Selected Employees</x-slot>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-900">Manage Selected Employees</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase
                                tracking-wider">First Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase
                                tracking-wider">Last Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase
                                tracking-wider">Email</th>
                            <th class="px-2 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($selectedEmployees as $employee)
                            <tr class="cursor-pointer accordion-toggle transition hover:bg-indigo-50"
                                data-employee-id="{{ $employee['employee_id'] }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $employee['fname'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $employee['lname'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $employee['email'] }}
                                </td>
                                <td class="px-2 py-4 text-right">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="chevron-icon h-5 w-5
                                        text-indigo-500 transition-transform duration-300" fill="none" viewBox="0 0
                                        24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </td>
                            </tr>
                            <tr class="hidden accordion-content bg-indigo-100"
                                id="accordion-{{ $employee['employee_id'] }}">
                                <td colspan="4" class="px-6 py-4">
                                    <div class="space-y-2">
                                        @if (isset($employeeApps[$employee['employee_id']]))
                                            @foreach ($employeeApps[$employee['employee_id']] as $app)
                                                <div
                                                    class="flex items-center justify-between p-2 bg-white rounded shadow">
                                                    <span>{{ $app->app_name }}</span>
                                                    <button
                                                        class="text-sm text-red-600 hover:text-red-800">Revoke</button>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-gray-600">No applications assigned.</p>
                                        @endif
                                        <div class="flex justify-center mt-2">
                                            <button class="text-indigo-600 hover:text-indigo-800 font-bold">
                                                &#43; Add Application
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.accordion-toggle');

            toggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const employeeId = this.getAttribute('data-employee-id');
                    const contentRow = document.getElementById(`accordion-${employeeId}`);
                    contentRow.classList.toggle('hidden');

                    // Rotate chevron
                    const chevron = this.querySelector('.chevron-icon');
                    chevron.classList.toggle('rotate-180');
                });
            });
        });
    </script>
</x-layout>
