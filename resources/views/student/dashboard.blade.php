<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[]" />
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 p-6">
        <!-- Total Hours Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="bg-secondary text-white rounded-t-xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">0/500</p>
                        <p class="text-white/80">Total Hours</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <i class="fa fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="#" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Tasks Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="bg-secondary text-white rounded-t-xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $tasks ?? 0 }}</p>
                        <p class="text-white/80">Active Tasks</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <i class="fa fa-list-check text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="#" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                    View Tasks <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Weekly Reports Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="bg-secondary text-white rounded-t-xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $reports ?? 0 }}</p>
                        <p class="text-white/80">Weekly Reports</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <i class="fa fa-file-lines text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="#" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                    View Reports <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Attendance Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="bg-secondary text-white rounded-t-xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $attendance ?? 0 }}</p>
                        <p class="text-white/80">Days Present</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <i class="fa fa-calendar-check text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="#" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                    View Attendance <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-6 pb-6">
        <!-- Weekly Reports -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="bg-secondary text-white rounded-t-xl p-4">
                <h3 class="font-bold text-lg">Weekly Reports</h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-between hover:bg-gray-100 transition-colors">
                    <div>
                        <p class="text-gray-900 font-medium">09/06/2024 - 09/20/2024</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Signed
                        </span>
                    </div>
                    <a href="#" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        View Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Attendance -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="bg-secondary text-white rounded-t-xl p-4">
                <h3 class="font-bold text-lg">Today's Attendance</h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-between hover:bg-gray-100 transition-colors">
                    <div>
                        <p class="text-gray-900 font-medium">Today's Schedule</p>
                        <p class="text-gray-500 text-sm">{{ date('F d, Y') }}</p>
                    </div>
                    <a href="#" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Check In
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>