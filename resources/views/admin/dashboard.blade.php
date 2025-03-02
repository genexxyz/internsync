<x-app-layout>
    
    <x-breadcrumbs :breadcrumbs="[
        
    ]" />

    
   <!-- Top Statistics Section -->
   <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 p-6">
    <!-- Students Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="bg-secondary text-white rounded-t-xl p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-4xl font-bold mb-1">{{ $students->count() ?? 0 }}</p>
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

    <!-- Instructors Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="bg-secondary text-white rounded-t-xl p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-4xl font-bold mb-1">{{ $instructors->count() ?? 0 }}</p>
                    <p class="text-white/80">Total Instructors</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <i class="fa fa-chalkboard-user text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="px-6 py-4">
            <a wire:navigate href="{{ route('admin.instructors') }}" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                View Details <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Companies Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="bg-secondary text-white rounded-t-xl p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-4xl font-bold mb-1">{{ $companies->count() ?? 0 }}</p>
                    <p class="text-white/80">Total Companies</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <i class="fa fa-building text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="px-6 py-4">
            <a href="#" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                View Details <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Deployments Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="bg-secondary text-white rounded-t-xl p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-4xl font-bold mb-1">{{ $deployments->count() ?? 0 }}</p>
                    <p class="text-white/80">Total Deployments</p>
                </div>
                <div class="bg-white/20 rounded-lg p-3">
                    <i class="fa fa-briefcase text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="px-6 py-4">
            <a href="#" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                View Details <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Main Section: Chart and Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-6 pb-6">
    <!-- Verification Chart Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 text-lg mb-6">Account Verification Status</h3>
        <div class="w-full">
            @livewire('admin.verification-chart')
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 text-lg mb-6">Quick Statistics</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-50 rounded-xl p-4 flex flex-col items-center justify-center">
                <span class="text-blue-600 font-semibold mb-1">Supervisors</span>
                <span class="text-3xl font-bold text-blue-700">{{ $supervisors->count() ?? 0 }}</span>
            </div>
            <div class="bg-green-50 rounded-xl p-4 flex flex-col items-center justify-center">
                <span class="text-green-600 font-semibold mb-1">Active Deployments</span>
                <span class="text-3xl font-bold text-green-700">{{ $deployments->count() ?? 0 }}</span>
            </div>
            <div class="bg-purple-50 rounded-xl p-4 flex flex-col items-center justify-center">
                <span class="text-purple-600 font-semibold mb-1">Partner Companies</span>
                <span class="text-3xl font-bold text-purple-700">{{ $companies->count() ?? 0 }}</span>
            </div>
            <div class="bg-orange-50 rounded-xl p-4 flex flex-col items-center justify-center">
                <span class="text-orange-600 font-semibold mb-1">Total Courses</span>
                <span class="text-3xl font-bold text-orange-700">{{ $courses->count() ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>
</x-app-layout>