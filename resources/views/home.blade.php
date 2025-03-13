<x-layout>
    <x-slot name="title">Home</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Home
        </h2>
    </x-slot>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-500 to-purple-500 py-16">
        <div class="max-w-7xl mx-auto text-center px-4">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">Welcome to the App Manager</h1>
            <p class="text-xl md:text-2xl text-indigo-100">Efficiently manage applications and employees, all in one place.</p>
        </div>
    </section>

    <!-- Bento Section -->
    <section class="max-w-7xl mx-auto my-16 px-4 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold text-indigo-600 mb-2">View Applications</h3>
            <p class="text-gray-600">Easily explore and manage detailed application records and their usage.</p>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold text-indigo-600 mb-2">Employee Management</h3>
            <p class="text-gray-600">Access comprehensive employee information, statuses, and application permissions.</p>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold text-indigo-600 mb-2">Data Insights</h3>
            <p class="text-gray-600">Analyze application usage and employee activity with intuitive reports.</p>
        </div>
    </section>

    <!-- Roadmap Section -->
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">What's Coming Next</h2>
            <ul class="space-y-4 text-gray-600 text-lg">
                <li class="flex items-start">
                    <span class="inline-block w-3 h-3 bg-indigo-500 rounded-full mr-4 mt-2"></span>
                    Enhanced user role management and permission settings
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-3 h-3 bg-indigo-500 rounded-full mr-4 mt-2"></span>
                    Real-time notifications and updates
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-3 h-3 bg-indigo-500 rounded-full mr-4 mt-2"></span>
                    Expanded analytics and reporting dashboards
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-3 h-3 bg-indigo-500 rounded-full mr-4 mt-2"></span>
                    Mobile-friendly interface for management on-the-go
                </li>
            </ul>
        </div>
    </section>
</x-layout>
