<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[]" />
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
        <!-- Total Students Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="bg-secondary text-white rounded-t-xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $totalStudents ?? 0 }}</p>
                        <p class="text-white/80">Total Students</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <i class="fa fa-users text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="#" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Deployed Students Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="bg-secondary text-white rounded-t-xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $deployedStudents ?? 0 }}</p>
                        <p class="text-white/80">Deployed Students</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <i class="fa fa-users-between-lines text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="{{ route('instructor.deployments.section') }}" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Not Deployed Students Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="bg-secondary text-white rounded-t-xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $notDeployedStudents ?? 0 }}</p>
                        <p class="text-white/80">Not Deployed</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <i class="fa fa-user-clock text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="{{ route('instructor.deployments.section') }}" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-6 pb-6">
        <!-- Attendance Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-6">Today's Attendance</h3>
            <div class="w-full">
                @livewire('instructor.attendance-chart')
            </div>
        </div>

        <!-- Quick Statistics -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-6">Quick Overview</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 rounded-xl p-4 flex flex-col items-center justify-center">
                    <span class="text-blue-600 font-semibold mb-1">Present Today</span>
                    <span class="text-3xl font-bold text-blue-700">{{ $presentToday ?? 0 }}</span>
                </div>
                <div class="bg-red-50 rounded-xl p-4 flex flex-col items-center justify-center">
                    <span class="text-red-600 font-semibold mb-1">Absent Today</span>
                    <span class="text-3xl font-bold text-red-700">{{ $absentToday ?? 0 }}</span>
                </div>
                <div class="bg-green-50 rounded-xl p-4 flex flex-col items-center justify-center">
                    <span class="text-green-600 font-semibold mb-1">Companies</span>
                    <span class="text-3xl font-bold text-green-700">{{ $companies ?? 0 }}</span>
                </div>
                <div class="bg-orange-50 rounded-xl p-4 flex flex-col items-center justify-center">
                    <span class="text-orange-600 font-semibold mb-1">Evaluated</span>
                    <span class="text-3xl font-bold text-orange-700">{{ $evaluated ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>