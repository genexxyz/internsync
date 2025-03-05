<div>
    @if(!$deployment || !$deployment->starting_date)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Your internship hasn't started yet. Weekly reports will be available once your starting date is set.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="space-y-6">
            <!-- Previous Reports Section -->
            @if($previousReports->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Previous Reports</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($previousReports as $report)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium text-gray-900">Week {{ $report->week_number }}</h4>
                                        <span @class([
                                            'px-2 py-1 text-xs font-medium rounded-full',
                                            'bg-green-100 text-green-800' => $report->status === 'approved',
                                            'bg-yellow-100 text-yellow-800' => $report->status === 'pending',
                                            'bg-red-100 text-red-800' => $report->status === 'rejected',
                                        ])>
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3">
                                        {{ Carbon\Carbon::parse($report->start_date)->format('M d') }} - 
                                        {{ Carbon\Carbon::parse($report->end_date)->format('M d, Y') }}
                                    </p>
                                    <button 
                                        wire:click="viewPastReport({{ $report->id }})"
                                        class="text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2"
                                    >
                                        <i class="fas fa-list-ul mr-1"></i> View Activities
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Current Week Indicator (when report exists) -->
            @if($reportExists)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Weekly report for Week {{ $weekNumber }} ({{ Carbon\Carbon::parse($startDate)->format('M d') }} - {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}) has been submitted.
                                </p>
                                {{-- <button 
                                    wire:click="viewWeekDetails"
                                    class="mt-2 text-secondary hover:text-secondary/80 text-sm font-medium flex items-center gap-2"
                                >
                                    <i class="fas fa-list-ul mr-1"></i> View Activities
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Full Weekly Report Form (when report doesn't exist) -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Week {{ $weekNumber }} Report</h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ Carbon\Carbon::parse($startDate)->format('M d') }} - 
                                    {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                                </p>
                            </div>
                            <button 
                                wire:click="viewWeekDetails"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                            >
                                <i class="fas fa-list-ul mr-2"></i>
                                View Activities
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <!-- Weekly Stats -->
                        <div class="mb-6 grid grid-cols-2 gap-4 border-b border-gray-200 pb-6">
                            <div>
                                <p class="text-sm text-gray-500">Journal Entries</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $journalCount }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Hours</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $this->formatHoursAndMinutes($weeklyTotal) }}</p>
                            </div>
                        </div>

                        <form wire:submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Learning Outcomes & Accomplishments
                                </label>
                                <div class="mt-1">
                                    <textarea
                                        wire:model="learningOutcomes"
                                        rows="6"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                        placeholder="Describe what you've learned and accomplished this week..."
                                    ></textarea>
                                </div>
                                @error('learningOutcomes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button 
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors duration-150"
                                >
                                    Submit Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Week Details Modal -->
        @if($showWeekDetails)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Week {{ $weekNumber }} Activities
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ Carbon\Carbon::parse($startDate)->format('M d') }} - 
                                            {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Total Hours: {{ $this->formatHoursAndMinutes($weeklyTotal) }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        @if(isset($selectedReport) || $reportExists)
                                            <button 
                                                wire:click="generatePdf"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-secondary hover:bg-secondary-dark focus:outline-none transition-colors duration-150"
                                            >
                                                <i class="fas fa-file-pdf mr-2"></i>
                                                Export PDF
                                            </button>
                                        @endif
                                        <button wire:click="$set('showWeekDetails', false)" class="text-gray-400 hover:text-gray-500">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Journal entries -->
                                <div class="space-y-4">
                                    @foreach($weeklyJournals as $date => $data)
                                        <div class="border rounded-lg p-4 {{ !$data['journal'] ? 'bg-gray-50' : '' }}">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">
                                                        {{ Carbon\Carbon::parse($date)->format('l, M d') }}
                                                    </h4>
                                                    @if($data['journal'] && $data['journal']->attendance)
                                                        <p class="text-sm text-gray-500 mt-1">
                                                            {{ $data['journal']->attendance->time_in ? Carbon\Carbon::parse($data['journal']->attendance->time_in)->format('h:i A') : 'No time in' }} - 
                                                            {{ $data['journal']->attendance->time_out ? Carbon\Carbon::parse($data['journal']->attendance->time_out)->format('h:i A') : 'No time out' }}
                                                        </p>
                                                    @else
                                                        <p class="text-sm text-red-500 mt-1">No Entry</p>
                                                    @endif
                                                </div>
                                                @if($data['daily_total'] !== '00:00')
    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
        {{ $this->formatHoursAndMinutes($data['daily_total']) }}
    </span>
@endif
                                            </div>
                                            @if($data['journal'])
                                                <p class="text-gray-600 text-sm">{{ $data['journal']->text }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Learning outcomes section (for past reports) -->
                                @if($selectedReport)
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <h4 class="font-medium text-gray-900 mb-2">Learning Outcomes</h4>
                                    <div class="bg-gray-50 p-3 rounded text-gray-700">
                                        {{ $selectedReport->learning_outcomes }}
                                    </div>
                                </div>
                                
                                <!-- Supervisor feedback section -->
                                @if($selectedReport->status !== 'pending')
    <div class="border-t border-gray-200 pt-4 mt-4">
        <h4 class="font-medium text-gray-900 mb-2">
            Supervisor Feedback
            @if($selectedReport->status === 'approved')
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Approved
                </span>
            @elseif($selectedReport->status === 'rejected')
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Rejected
                </span>
            @endif
        </h4>
        <div class="bg-blue-50 p-3 rounded text-gray-700 border-l-4 border-blue-400">
            @if($selectedReport->supervisor_feedback)
                <p class="italic text-sm">{{ $selectedReport->supervisor_feedback }}</p>
            @else
                <p class="italic text-sm text-gray-500">No feedback provided.</p>
            @endif
            @if($selectedReport->reviewed_at)
                <p class="text-xs text-gray-500 mt-2">
                    Reviewed on {{ Carbon\Carbon::parse($selectedReport->reviewed_at)->format('M d, Y \a\t h:i A') }}
                </p>
            @endif
        </div>
    </div>
@endif
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>