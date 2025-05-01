<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[]" />

    <!-- Top Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 p-6">
        <!-- Students Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $students->count() ?? 0 }}</p>
                        <p class="text-white/90">Total Students</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3 backdrop-blur-sm">
                        <i class="fa fa-users text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="#" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors gap-2 w-full justify-center">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Instructors Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-violet-600 to-purple-700 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $instructors->count() ?? 0 }}</p>
                        <p class="text-white/90">Total Instructors</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3 backdrop-blur-sm">
                        <i class="fa fa-chalkboard-user text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a wire:navigate href="{{ route('admin.instructors') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 transition-colors gap-2 w-full justify-center">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Companies Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-green-700 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $companies->count() ?? 0 }}</p>
                        <p class="text-white/90">Total Companies</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3 backdrop-blur-sm">
                        <i class="fa fa-building text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="{{ route('admin.company') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition-colors gap-2 w-full justify-center">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Deployments Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $deployments->count() ?? 0 }}</p>
                        <p class="text-white/90">Total Deployments</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3 backdrop-blur-sm">
                        <i class="fa fa-briefcase text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="{{ route('admin.courses') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-orange-50 text-orange-700 hover:bg-orange-100 transition-colors gap-2 w-full justify-center">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Section: Chart and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-6 pb-6">
        <!-- Verification Chart Section -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white p-4">
                <h3 class="font-bold text-lg">Account Verification Status</h3>
            </div>
            <div class="p-6">
                @livewire('admin.verification-chart')
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white p-4">
                <h3 class="font-bold text-lg">Quick Statistics</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="group bg-blue-50 rounded-xl p-6 flex flex-col items-center justify-center hover:bg-blue-100 transition-colors">
                        <span class="text-blue-600 font-semibold mb-2">Supervisors</span>
                        <span class="text-4xl font-bold text-blue-700">{{ $supervisors->count() ?? 0 }}</span>
                    </div>
                    <div class="group bg-green-50 rounded-xl p-6 flex flex-col items-center justify-center hover:bg-green-100 transition-colors">
                        <span class="text-green-600 font-semibold mb-2">Active Deployments</span>
                        <span class="text-4xl font-bold text-green-700">{{ $deployments->count() ?? 0 }}</span>
                    </div>
                    <div class="group bg-purple-50 rounded-xl p-6 flex flex-col items-center justify-center hover:bg-purple-100 transition-colors">
                        <span class="text-purple-600 font-semibold mb-2">Partner Companies</span>
                        <span class="text-4xl font-bold text-purple-700">{{ $companies->count() ?? 0 }}</span>
                    </div>
                    <div class="group bg-orange-50 rounded-xl p-6 flex flex-col items-center justify-center hover:bg-orange-100 transition-colors">
                        <span class="text-orange-600 font-semibold mb-2">Total Courses</span>
                        <span class="text-4xl font-bold text-orange-700">{{ $courses->count() ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>