<x-layout>  
    <x-slot name="title">{{ $employee->fname }} {{ $employee->lname }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Employee Details: {{ $employee->fname }} {{ $employee->lname }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $employee->fname }} {{ $employee->lname }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Detailed employee information.
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">
                            First Name
                        </dt>
                        <dd class="mt-0 text-sm text-gray-900 col-span-2">
                            {{ $employee->fname }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">
                            Last Name
                        </dt>
                        <dd class="mt-0 text-sm text-gray-900 col-span-2">
                            {{ $employee->lname }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">
                            Department
                        </dt>
                        <dd class="mt-0 text-sm text-gray-900 col-span-2">
                            {{ $employee->division }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">
                            HR Status
                        </dt>
                        <dd class="mt-0 text-sm text-gray-900 col-span-2">
                            {{ $employee->active }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">
                            Email Address
                        </dt>
                        <dd class="mt-0 text-sm text-gray-900 col-span-2">
                            {{ $employee->email }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layout>
