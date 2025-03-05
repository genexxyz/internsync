<div>
    @if(auth()->user()->is_verified)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Weekly Reports Monitoring</h2>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($students as $student)
                @php
                    // Get the latest weekly report for this student
                    $latestReport = $student->weeklyReports->sortByDesc('submitted_at')->first();
                    $timeAgo = $latestReport ? $latestReport->submitted_at->diffForHumans() : null;
                @endphp
                <div class="p-6">
                    <!-- Student Info Header -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500 text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </h3>
                                @if($latestReport)
                                    <div class="mt-1 flex flex-col sm:flex-row sm:items-center sm:gap-2">
                                        <span class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-file-alt text-gray-400 mr-1"></i>
                                            Latest: Week {{ $latestReport->week_number }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-clock text-gray-400 mr-1"></i>
                                            Submitted {{ $timeAgo }}
                                        </span>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 mt-1">No reports submitted yet</p>
                                @endif
                            </div>
                        </div>
                        
                        <button 
                            wire:click="toggleExpand({{ $student->id }})"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 
                                hover:bg-gray-50 transition-colors duration-150"
                        >
                            <span>View Reports</span>
                            <i class="fas fa-chevron-down ml-2 transition-transform duration-200 
                                {{ $expandedStudent === $student->id ? 'transform rotate-180' : '' }}"></i>
                        </button>
                    </div>

                    <!-- Weekly Reports List -->
                    @if($expandedStudent === $student->id)
                        <div class="mt-6 border rounded-lg divide-y"
                             x-show="true"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                        >
                            @forelse($student->weeklyReports as $report)
                                <div class="p-4 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">Week {{ $report->week_number }}</h4>
                                            <p class="text-sm text-gray-500">
                                                {{ Carbon\Carbon::parse($report->start_date)->format('M d') }} - 
                                                {{ Carbon\Carbon::parse($report->end_date)->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span @class([
                                                'px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                'bg-green-100 text-green-800' => $report->status === 'approved',
                                                'bg-yellow-100 text-yellow-800' => $report->status === 'pending',
                                                'bg-red-100 text-red-800' => $report->status === 'rejected'
                                            ])>
                                                {{ ucfirst($report->status) }}
                                            </span>
                                            <button 
                                                wire:click="viewReport({{ $report->id }})" 
                                                class="text-blue-600 hover:text-blue-800"
                                            >
                                                View Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-gray-500">
                                    No weekly reports submitted yet.
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    No students found under your supervision.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Include the Weekly Report Review Modal Component -->
    @livewire('supervisor.weekly-report-review')
    @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Your account is pending verification. You'll be able to manage interns once your account is verified.
                        </p>
                    </div>
                </div>
            </div>
        @endif
</div>