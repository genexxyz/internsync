<div>
    @error('deployment')
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        {{ $message }}
                    </p>
                </div>
            </div>
        </div>
    @enderror

    @error('journal')
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        {{ $message }}
                    </p>
                </div>
            </div>
        </div>
    @enderror

    @if(!$errors->has('deployment') && !$errors->has('journal'))
<div>
    <!-- Current Week Section -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Week {{ $weekNumber }} Report
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ Carbon\Carbon::parse($startDate)->format('M d') }} -
                        {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                    </p>
                </div>
                @if($reportExists)
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full {{ 
                            $currentReport?->status === 'approved' ? 'bg-green-100 text-green-800' : 
                            ($currentReport?->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')
                        }}">
                            {{ $currentReport?->status === 'approved' ? 'Report Approved' : 
                               ($currentReport?->status === 'rejected' ? 'Report Rejected' : 'Pending Approval') 
                            }}
                        </span>
                    </div>
                @else
                    <div class="flex items-center space-x-3">
                        <button wire:click="viewWeekDetails" class="btn-primary">
                            <i class="fas fa-list-ul mr-2"></i> View Activities
                        </button>
                    </div>
                @endif
            </div>
        </div>

        @if(!$reportExists)
    <div class="p-6">
        <form wire:submit.prevent="submit" class="space-y-6">
            <!-- Date Selection Section -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-4">Report Period</h4>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <div class="mt-1 relative">
                            <input 
                                type="date" 
                                wire:model.live="startDate"
                                min="{{ $minStartDate }}"
                                max="{{ now()->format('Y-m-d') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                required
                            >
                            <x-input-error :messages="$errors->get('startDate')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <div class="mt-1 relative">
                            <input 
                                type="date" 
                                wire:model.live="endDate"
                                min="{{ $startDate }}"
                                max="{{ $maxEndDate }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                @if(!$startDate) disabled @endif
                                required
                            >
                            <x-input-error :messages="$errors->get('endDate')" class="mt-2" />
                        </div>
                    </div>
                </div>
                @if($startDate && $endDate)
                    <p class="mt-3 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Selected period: {{ Carbon\Carbon::parse($startDate)->diffInDays($endDate) + 1 }} days
                    </p>
                @endif
            </div>

            <!-- Learning Outcomes Section -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-sm font-medium text-gray-700">Learning Outcomes & Accomplishments</h4>
                    <span class="text-xs text-gray-500">Minimum 50 characters</span>
                </div>
                <div class="relative">
                    <textarea 
                        wire:model="learningOutcomes" 
                        rows="6" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary resize-none"
                        placeholder="Describe what you've learned and accomplished this week..."
                        required
                    ></textarea>
                    <x-input-error :messages="$errors->get('learningOutcomes')" class="mt-2" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button 
                    type="submit" 
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-md shadow-sm transition-colors"
                >
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Weekly Report
                </button>
            </div>
        </form>
    </div>
@else
            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-medium text-gray-700">Report Summary</h4>
                        
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        <span class="font-medium">Submitted:</span> 
                        {{ Carbon\Carbon::parse($currentReport->submitted_at)->format('M d, Y h:i A') ?? 'N/A' }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- Previous Reports Section -->
    @if($previousReports->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Previous Reports</h3>
            </div>
            <div class="p-6 grid grid-cols-3 gap-4">
                @foreach($previousReports as $report)
                
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <h4 class="font-medium text-gray-900">Week {{ $report->week_number }}</h4>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ 
                                $report->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                ($report->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')
                            }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ Carbon\Carbon::parse($report->start_date)->format('M d') }} -
                            {{ Carbon\Carbon::parse($report->end_date)->format('M d, Y') }}
                        </p>
                        <button wire:click="viewPastReport({{ $report->id }})" 
                            class="mt-3 text-secondary hover:text-secondary-dark">
                            <i class="fas fa-list-ul mr-1"></i> View Details
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Week Details Modal -->
    @if($showWeekDetails)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                    <!-- Modal Header -->
                    <div class="bg-white px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Weekly Activities (Week {{ $weekNumber }})
                                </h3>
                                <div class="mt-1">
                                    <span class="text-sm text-gray-600">
                                        {{ Carbon\Carbon::parse($startDate)->format('F d') }} - 
                                        {{ Carbon\Carbon::parse($endDate)->format('F d, Y') }}
                                    </span>
                                    @if($selectedReport || $reportExists)
                                    
    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ 
        ($selectedReport?->status ?? $currentReport?->status) === 'approved' ? 'bg-green-100 text-green-800' : 
        (($selectedReport?->status ?? $currentReport?->status) === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')
    }}">
        {{ ucfirst($selectedReport?->status ?? $currentReport?->status) }}
    </span>
@endif
                                </div>
                            </div>
                            <button wire:click="$set('showWeekDetails', false)" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4 max-h-[calc(100vh-200px)] overflow-y-auto">
                        
                        <div class="space-y-4">
                            @foreach($weeklyJournals as $date => $journal)
                                <x-daily-entry :journal="$journal" :date="$date" />
                            @endforeach
                        </div>
                        @if($selectedReport || $reportExists)
                            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Learning Outcomes & Accomplishments:</h4>
                                <p class="text-sm text-gray-600">
                                    {{ $selectedReport?->learning_outcomes ?? $currentReport?->learning_outcomes }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-end">
                            @if($selectedReport || $reportExists)
                                <div class="flex items-center space-x-3">
                                    <button 
                                        wire:click="generatePdf"
                                        class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-md"
                                    >
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        Generate PDF
                                    </button>
                                    <button 
                                        wire:click="$set('showWeekDetails', false)"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                    >
                                        Close
                                    </button>
                                </div>
                            @else
                                <button 
                                    wire:click="$set('showWeekDetails', false)"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                >
                                    Close
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endif
</div>