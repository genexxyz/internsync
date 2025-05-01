<div class="space-y-6">
    @if($student->deployment && $student->deployment->status !== 'pending')
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-semibold text-gray-800 mb-4">Attendance Summary</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Total Days</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $student->attendances->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Hours Rendered</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalHours }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Completion</p>
                    <p class="text-2xl font-semibold {{ $progressPercentage >= 100 ? 'text-green-600' : 'text-blue-600' }}">
                        {{ $progressPercentage }}%
                    </p>
                </div>
            </div>
        </div>

        @if($student->deployment->evaluation)
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-4">Supervisor Evaluation</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Rating</label>
                        <p class="text-gray-800">{{ $student->deployment->evaluation->total_score }}/100</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Evaluated On</label>
                        <p class="text-gray-800">{{ $student->deployment->evaluation->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-500">Comments</label>
                        <p class="text-gray-800">{{ $student->deployment->evaluation->recommendation }}</p>
                    </div>
                </div>
                <div class="border-t mt-4 pt-4">
                
                    @if($student->deployment->evaluation)
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-800">Evaluation Report</h4>
                            <button wire:click="downloadEvaluation"
                                            class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                            <i class="fa fa-download mr-2"></i>
                                            Download
                                        </button>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No acceptance letter uploaded</p>
                    @endif
                </div>
            </div>
            
        @endif
    @else
        <div class="text-center py-8">
            <p class="text-gray-500">Reports will be available once deployment starts</p>
        </div>
    @endif
</div>