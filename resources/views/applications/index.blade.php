<x-layout> 
    <x-slot name="title">Applications</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Application Search
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-semibold mb-2">Welcome to Applications Page</h2>
            <p class="text-lg text-gray-600">Below are all the applications used/developed at HPD.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($applications as $application)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-6 py-4">
                        <h3 class="font-bold text-xl text-gray-800 mb-2">
                            {{ $application->app_name }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            {{ $application->description }}
                        </p>
                        <a href="{{ route('applications.show', $application->id) }}" class="inline-block text-indigo-500 hover:text-indigo-700 font-semibold">
                            Show Application &rarr;
                        </a>                        
                    </div>
                    <div class="px-6 py-2 bg-gray-50 border-t text-xs text-gray-500 flex justify-between">
                        <span>Type: {{ $application->applicationType->type_name }}</span>
                        <span class="font-semibold">{{ $application->employee_applications_count }} Users</span>
                    </div>                    
                </div>
            @endforeach
        </div>

        @if($applications->isEmpty())
            <div class="text-center mt-10">
                <p class="text-gray-600">No applications found.</p>
            </div>
        @endif
    </div>
</x-layout>