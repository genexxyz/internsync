<div class="space-y-6">
    @if($student->deployment)
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-semibold text-gray-800 mb-4">Acceptance Letter</h4>
            @if($student->deployment->supervisor_id)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Uploaded on {{ $student->acceptance_letter->created_at->format('M d, Y') }}</span>
                    <button wire:click="downloadAcceptanceLetter"
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                    <i class="fa fa-download mr-2"></i>
                                    Download
                                </button>
                </div>
            @else
                <p class="text-gray-500 italic">No acceptance letter uploaded</p>
            @endif
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-semibold text-gray-800 mb-4">Weekly Reports</h4>
            @if($student->weeklyReports->count() > 0)
                <div class="space-y-3">
                    @foreach($student->weeklyReports as $report)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-800">Week {{ $report->week_number }}</p>
                                <span class="text-xs text-gray-500">{{ $report->created_at->format('M d, Y') }}</span>
                            </div>
                            <button wire:click="generateWeeklyReport({{ $report->id }})"
                                class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                <i class="fa fa-download mr-2"></i>
                                Download
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic">No weekly reports submitted</p>
            @endif
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500">Documents will be available after deployment</p>
        </div>
    @endif
</div>