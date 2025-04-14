<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('student.journey'), 'label' => 'OJT Journey']]" />

    <!-- Company and Supervisor Info -->
    <div class="my-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Company Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 flex items-start justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500">Company/Client Name</p>
                    <p class="text-xl font-semibold text-gray-900">
                        {{ $deployment->company->company_name ?? 'Not Assigned Yet' }}
                    </p>
                    @if($deployment->company && $deployment->department)
                        <div class="flex items-center mt-2">
                            <i class="fas fa-users text-gray-400 mr-2"></i>
                            <p class="text-sm text-gray-600">{{ $deployment->department->department_name }}</p>
                        </div>
                    @endif
                </div>
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 bg-blue-50 flex items-center justify-center rounded-lg">
                        <i class="fa fa-building text-2xl text-blue-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supervisor Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 flex items-start justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500">Supervisor</p>
                    <p class="text-xl font-semibold text-gray-900">
                        {{ $deployment->supervisor ? $deployment->supervisor->first_name . ' ' . $deployment->supervisor->last_name : 'Not Assigned' }}
                    </p>
                    @if($deployment->supervisor && $deployment->supervisor->email)
                        <div class="flex items-center mt-2">
                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                            <p class="text-sm text-gray-600">{{ $deployment->supervisor->email }}</p>
                        </div>
                    @endif
                </div>
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 bg-green-50 flex items-center justify-center rounded-lg">
                        <i class="fa fa-user-tie text-2xl text-green-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-6">
        <!-- Starting Date -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-5">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-500">Starting Date</p>
                    <div class="h-8 w-8 bg-blue-50 flex items-center justify-center rounded-lg">
                        <i class="fas fa-calendar text-blue-500"></i>
                    </div>
                </div>
                <p class="text-2xl font-semibold text-gray-900">
                    {{ $deployment->starting_date ? $deployment->starting_date->format('M d, Y') : 'Not Set' }}
                </p>
            </div>
        </div>

        <!-- Hours Progress -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-5">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-500">Hours Progress</p>
                    <div class="h-8 w-8 bg-purple-50 flex items-center justify-center rounded-lg">
                        <i class="fas fa-clock text-purple-500"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $hours_rendered ?? 0 }}/{{ $deployment->custom_hours ?? 500 }}
                    </p>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ ($hours_rendered ?? 0) / ($deployment->custom_hours ?? 500) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Date -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-5">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-500">End Date</p>
                    <div class="h-8 w-8 bg-green-50 flex items-center justify-center rounded-lg">
                        <i class="fas fa-flag-checkered text-green-500"></i>
                    </div>
                </div>
                <p class="text-2xl font-semibold text-gray-900">
                    {{ $deployment->ending_date ? $deployment->ending_date->format('M d, Y') : 'Not Set' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Attendance Calendar</h2>
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 gap-2"><i class="fa fa-file-pdf"></i>Generate DTR</button>
        </div>
        <div class="p-6">
            @livewire('calendar')
        </div>
    </div>
</x-app-layout>