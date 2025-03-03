<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('student.taskAttendance'), 'label' => 'Task & Attendance']]" />
    
    <div class="m-6">
        <!-- Task & Attendance Section -->
        
            @livewire('student.task-attendance')
            <div class="mx-6">
                <livewire:student.weekly-report-generator />
            </div>
            
        
        


        <!-- Weekly Reports Section -->
        {{-- <div class="bg-white rounded-xl shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <h2 class="text-lg font-bold text-gray-800">Weekly Reports</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Report Generator -->
                    <div class="lg:col-span-2 bg-gray-50 rounded-xl p-6">
                        <h3 class="font-semibold text-gray-700 mb-4">Generate Report</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                                @livewire('datepicker')
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                                @livewire('datepicker')
                            </div>
                            <div class="flex justify-end">
                                <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <i class="fa fa-file-export mr-2"></i>
                                    Generate
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submitted Reports -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="font-semibold text-gray-700 mb-4">Submitted Reports</h3>
                        <div class="flex justify-end">
                            <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="fa fa-eye mr-2"></i>
                                View All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <!-- Recent Activity -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">
                            Weekly Reports from <span class="font-medium">09/07/2024</span> to <span class="font-medium">09/11/2024</span> 
                            has been signed by your supervisor on <span class="font-medium">09/11/2024</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fa fa-eye mr-2"></i>
                            View
                        </button>
                        <button class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <i class="fa fa-paper-plane mr-2"></i>
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</x-app-layout>