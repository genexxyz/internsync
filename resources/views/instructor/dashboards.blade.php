<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[]" />
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
        <!-- Total Students Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $totalStudents ?? 0 }}</p>
                        <p class="text-white/90">Total Students</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3 backdrop-blur-sm">
                        <i class="fa fa-users text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="{{ route('instructor.deployments.section') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors gap-2 w-full justify-center">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Deployed Students Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-green-700 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $deployedStudents ?? 0 }}</p>
                        <p class="text-white/90">Deployed Students</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3 backdrop-blur-sm">
                        <i class="fa fa-users-between-lines text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="{{ route('instructor.deployments.section') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition-colors gap-2 w-full justify-center">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Not Deployed Students Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $notDeployedStudents ?? 0 }}</p>
                        <p class="text-white/90">Not Deployed</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3 backdrop-blur-sm">
                        <i class="fa fa-user-clock text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="{{ route('instructor.deployments.section') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-orange-50 text-orange-700 hover:bg-orange-100 transition-colors gap-2 w-full justify-center">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-6 pb-6">
        <!-- Attendance Chart -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white p-4">
                <h3 class="font-bold text-lg">Today's Attendance</h3>
            </div>
            <div class="p-6">
                @livewire('instructor.attendance-chart')
            </div>
        </div>

        <!-- Quick Statistics -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white p-4">
                <h3 class="font-bold text-lg">Quick Overview</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="group bg-blue-50 rounded-xl p-6 flex flex-col items-center justify-center hover:bg-blue-100 transition-colors">
                        <span class="text-blue-600 font-semibold mb-2">Present Today</span>
                        <span class="text-4xl font-bold text-blue-700">{{ $presentToday ?? 0 }}</span>
                    </div>
                    <div class="group bg-red-50 rounded-xl p-6 flex flex-col items-center justify-center hover:bg-red-100 transition-colors">
                        <span class="text-red-600 font-semibold mb-2">Absent Today</span>
                        <span class="text-4xl font-bold text-red-700">{{ $absentToday ?? 0 }}</span>
                    </div>
                    <div class="group bg-green-50 rounded-xl p-6 flex flex-col items-center justify-center hover:bg-green-100 transition-colors">
                        <span class="text-green-600 font-semibold mb-2">Companies</span>
                        <span class="text-4xl font-bold text-green-700">{{ $companies ?? 0 }}</span>
                    </div>
                    <div class="group bg-orange-50 rounded-xl p-6 flex flex-col items-center justify-center hover:bg-orange-100 transition-colors">
                        <span class="text-orange-600 font-semibold mb-2">Evaluated</span>
                        <span class="text-4xl font-bold text-orange-700">{{ $evaluated ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>