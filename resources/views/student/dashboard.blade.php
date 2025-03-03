<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[]" />
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 p-6">
        <!-- Total Hours Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="bg-secondary text-white rounded-t-xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">
                            {{ $totalHours ?? '00:00' }}/{{ $deployment->custom_hours ?? '00:00' }}
                        </p>
                        <div class="flex items-center gap-2 text-white/80">
                            <i class="fa fa-clock-rotate-left text-sm"></i>
                            <p>Total Hours</p>
                        </div>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <i class="fa fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="{{ route('student.journey') }}" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                    View Details <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Weekly Reports Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="bg-secondary text-white rounded-t-xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $weeklyReports->count() }}</p>
                        <div class="flex items-center gap-2 text-white/80">
                            <i class="fa fa-file-pen text-sm"></i>
                            <p>Weekly Reports</p>
                        </div>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <i class="fa fa-file-lines text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="{{ route('student.taskAttendance') }}" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
                    View Reports <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Attendance Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="bg-secondary text-white rounded-t-xl p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-4xl font-bold mb-1">{{ $attendanceCount }}</p>
                        <div class="flex items-center gap-2 text-white/80">
                            <i class="fa fa-calendar-check text-sm"></i>
                            <p>Days Present</p>
                        </div>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <i class="fa fa-calendar-check text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <a href="{{ route('student.taskAttendance') }}" class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2">
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
                <h3 class="font-bold text-lg">Recent Weekly Reports</h3>
            </div>
            <div class="p-4 space-y-4">
                @forelse($weeklyReports as $report)
                    <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-between hover:bg-gray-100 transition-colors">
                        <div>
                            <p class="text-gray-900 font-medium">Week {{ $report->week_number }}</p>
                            <p class="text-gray-500 text-sm">
                                {{ Carbon\Carbon::parse($report->start_date)->format('M d') }} - 
                                {{ Carbon\Carbon::parse($report->end_date)->format('M d, Y') }}
                            </p>
                            <span @class([
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2',
                                'bg-green-100 text-green-800' => $report->status === 'approved',
                                'bg-yellow-100 text-yellow-800' => $report->status === 'pending',
                                'bg-red-100 text-red-800' => $report->status === 'rejected'
                            ])>
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>
                        <a href="{{ route('student.taskAttendance', $report->id) }}" 
                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            View Report
                        </a>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <p>No weekly reports submitted yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Today's Attendance -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="bg-secondary text-white rounded-t-xl p-4">
                <h3 class="font-bold text-lg">Today's Attendance</h3>
            </div>
            <div class="p-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-gray-900 font-medium">{{ now()->format('l, F d, Y') }}</p>
                            @if($todayAttendance)
                                <p class="text-gray-500 text-sm mt-1">
                                    Time In: {{ $todayAttendance->time_in ? Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') : 'Not yet' }}
                                </p>
                                @if($todayAttendance->time_out)
                                    <p class="text-gray-500 text-sm">
                                        Time Out: {{ Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i A') }}
                                    </p>
                                    <p class="text-gray-500 text-sm">
                                        Total Hours: {{ $todayAttendance->total_hours }}
                                    </p>
                                @endif
                            @else
                                <p class="text-gray-500 text-sm mt-1">No attendance record yet</p>
                            @endif
                        </div>
                        <div>
                            @if(!$todayAttendance)
                                <a href="{{ route('student.taskAttendance') }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Time In
                                </a>
                            @elseif(!$todayAttendance->time_out)
                                <a href="{{ route('student.taskAttendance') }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                    Time Out
                                </a>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Completed
                                </span>
                            @endif
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>