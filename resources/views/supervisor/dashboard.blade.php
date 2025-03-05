<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[]" />
    <!-- Company Info -->
    <div class="p-6 pb-0">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 flex items-start justify-between border-b border-gray-100">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ auth()->user()->supervisor->company->company_name }}</h1>
                    <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                        <span>
                            <i class="fa fa-users mr-1.5"></i>
                            {{ auth()->user()->supervisor->department->department_name ?? 'No Department' }}
                        </span>
                        <span class="text-gray-300">•</span>
                        <span>
                            <i class="fa fa-location-dot mr-1.5"></i>
                            {{ auth()->user()->supervisor->company->address }}
                        </span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    @if(auth()->user()->supervisor->department_name)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                            Department Supervisor
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-50 text-gray-700">
                            Company Supervisor
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6 overflow-hidden">
        <!-- Total Handled Interns -->
        <div class="rounded-xl shadow-sm bg-gradient-to-r from-secondary to-secondary/90 hover:shadow-md transition-shadow">
            <div class="px-6 py-5 flex justify-between items-center">
                <div class="text-white">
                    <p class="text-5xl font-bold tracking-tight">{{$supervisor->deployments->count() ?? 0}}</p>
                    <p class="mt-1 text-lg font-medium">Handled Interns</p>
                    <p class="text-sm text-white/80 mt-1">Total handled interns</p>
                </div>
                <div class="bg-white/10 p-3 rounded-lg">
                    <i class="fa fa-users text-white text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- On-going Interns -->
        <div class="rounded-xl shadow-sm bg-white hover:shadow-md transition-shadow">
            <div class="px-6 py-5">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-5xl font-bold tracking-tight text-gray-900">{{ $supervisor->deployments->where('status', 'ongoing')->count() ?? 0 }}</p>
                        <p class="mt-1 text-lg font-medium text-gray-600">On-going</p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <i class="fa fa-users-between-lines text-blue-500 text-3xl"></i>
                    </div>
                </div>
                <a href="#" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                    View Interns <i class="fa fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Finished Interns -->
        <div class="rounded-xl shadow-sm bg-white hover:shadow-md transition-shadow">
            <div class="px-6 py-5">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-5xl font-bold tracking-tight text-gray-900">{{ $supervisor->deployments->where('status', 'completed')->count() ?? 0 }}</p>
                        <p class="mt-1 text-lg font-medium text-gray-600">Finished</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fa fa-user-check text-green-500 text-3xl"></i>
                    </div>
                </div>
                <a href="#" class="inline-flex items-center text-sm text-green-600 hover:text-green-700">
                    View Interns <i class="fa fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="p-6">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Recent Notifications</h2>
            </div>
            <div class="divide-y divide-gray-100">
                <!-- Notification Item -->
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                                <i class="fa fa-file-lines text-blue-500"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                Juan Dela Cruz submitted his report
                                <span class="text-gray-400">from 09/16/2024 to 9/20/2024</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                        </div>
                    </div>
                    <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100">
                        View Report
                    </a>
                </div>

                <!-- Notification Item -->
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                                <i class="fa fa-file-lines text-blue-500"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                Danilo Cruz submitted his report
                                <span class="text-gray-400">from 09/16/2024 to 9/20/2024</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-1">3 hours ago</p>
                        </div>
                    </div>
                    <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100">
                        View Report
                    </a>
                </div>
            </div>

            <!-- View All Link -->
            <div class="px-6 py-4 border-t border-gray-100">
                <a href="#" class="text-sm text-gray-500 hover:text-gray-700 flex items-center justify-center">
                    View All Notifications <i class="fa fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>