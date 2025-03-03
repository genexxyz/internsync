<!-- filepath: /opt/lampp/htdocs/internsync/resources/views/livewire/student/task-attendance.blade.php -->
<div class="mb-6">
    @if(!$deployment || !$deployment->starting_date)
        <!-- Not Started Message -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Internship Not Started</h3>
                    <p class="text-gray-600">Your internship starting date has not been set yet. Please wait for your supervisor to set your starting date.</p>
                </div>
            </div>
        </div>
    @elseif(Carbon\Carbon::parse($deployment->starting_date)->isAfter(now()))
        <!-- Future Start Date Message -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                        <i class="fas fa-calendar text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Internship Starts Soon</h3>
                    <p class="text-gray-600 mb-2">Your internship is scheduled to start on:</p>
                    <p class="text-lg font-semibold text-blue-600">
                        {{ Carbon\Carbon::parse($deployment->starting_date)->format('F d, Y') }}
                    </p>
                    <p class="text-sm text-gray-500 mt-4">You'll be able to access this section once your internship begins.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-secondary p-6">
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <h2 class="text-2xl font-bold text-white">Task & Attendance</h2>
                        <p class="text-white/80">
                            <i class="fa fa-calendar-day mr-2"></i>
                            {{ Carbon\Carbon::parse($dayToday)->format('F d, Y') }}
                        </p>
                    </div>
                    <div class="text-white/80">
                        <p class="text-sm">Current Time</p>
                        <p class="text-2xl font-semibold">{{ $currentTime }}</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasks</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Break</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Hours</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <!-- Time In Cell -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-start space-y-2">
                                    @if (!$attendance)
                                        <button wire:click="timeIn" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors duration-300">
                                            <i class="fa fa-sign-in-alt mr-2"></i>
                                            Time In
                                        </button>
                                    @else
                                        <div class="flex items-center space-x-2 text-green-600">
                                            <i class="fa fa-check-circle text-lg"></i>
                                            <span class="font-medium">{{ Carbon\Carbon::parse($attendance->time_in)->format('h:i A') }}</span>
                                        </div>
                                        <span class="text-xs text-gray-500">Recorded</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Tasks Cell -->
                            <td class="px-6 py-4">
                                <button 
                                    wire:click="toggleJournalModal"
                                    @class([
                                        'inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-300',
                                        'bg-blue-600 hover:bg-blue-700 text-white' => !$isSubmitted && $attendance,
                                        'bg-gray-100 text-gray-400 cursor-not-allowed' => !$attendance || $isSubmitted
                                    ])
                                    @if (!$attendance || $isSubmitted) disabled @endif
                                >
                                    <i class="fa fa-book mr-2"></i>
                                    {{ $existingJournal ? 'Edit Journal' : 'Add Journal' }}
                                </button>

                                @if ($existingJournal)
                                    <div class="mt-2 flex items-center space-x-2">
                                        <span class="text-xs text-gray-500">Updated {{ $existingJournal->updated_at->diffForHumans() }}</span>
                                        <span @class([
                                            'px-2 py-1 text-xs font-medium rounded-full',
                                            'bg-green-100 text-green-800' => $existingJournal->remarks === 'done',
                                            'bg-yellow-100 text-yellow-800' => $existingJournal->remarks === 'pending'
                                        ])>
                                            {{ ucfirst($existingJournal->remarks) }}
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <!-- Break Cell -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    @if ($attendance && !$attendance->time_out)
                                        @if (!$attendance->start_break)
                                            <button wire:click="startBreak" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors duration-300">
                                                <i class="fa fa-coffee mr-2"></i>
                                                Start Break
                                            </button>
                                        @elseif (!$attendance->end_break)
                                            <button wire:click="endBreak" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors duration-300">
                                                <i class="fa fa-stop-circle mr-2"></i>
                                                End Break
                                            </button>
                                        @endif
                                        @else
                                        <div class="text-sm text-gray-600">
                                            No Break
                                        </div>
                                    @endif

                                    @if ($attendance && $attendance->start_break && $attendance->end_break)
                                        <div class="text-sm text-gray-600">
                                            <i class="fa fa-clock mr-2"></i>
                                            {{ Carbon\Carbon::parse($attendance->start_break)->format('h:i A') }} - 
                                            {{ Carbon\Carbon::parse($attendance->end_break)->format('h:i A') }}
                                        </div>
                                        
                                    @endif
                                </div>
                            </td>

                            <!-- Time Out Cell -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-start space-y-2">
                                    @if ($attendance && !$attendance->time_out)
                                        <button 
                                            wire:click="timeOut"
                                            @class([
                                                'inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-300',
                                                'bg-red-500 hover:bg-red-600 text-white' => !$isSubmitted,
                                                'bg-gray-100 text-gray-400 cursor-not-allowed' => $isSubmitted
                                            ])
                                            @if($isSubmitted) disabled @endif
                                        >
                                            <i class="fa fa-sign-out-alt mr-2"></i>
                                            Time Out
                                        </button>
                                    @elseif ($attendance && $attendance->time_out)
                                        <div class="flex items-center space-x-2 text-red-600">
                                            <i class="fa fa-check-circle text-lg"></i>
                                            <span class="font-medium">{{ Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Total Hours Cell -->
                            <td class="px-6 py-4">
                                <div class="text-lg font-semibold text-gray-700">
                                    {{ $attendance ? $attendance->total_hours : '00:00:00' }}
                                </div>
                            </td>

                            <!-- Actions Cell -->
                            <td class="px-6 py-4">
                                @if (!$isSubmitted && $attendance && $attendance->time_out && $existingJournal)
                                    <button wire:click="submitJournal" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors duration-300">
                                        <i class="fa fa-paper-plane mr-2"></i>
                                        Submit
                                    </button>
                                @elseif ($isSubmitted)
                                    <div class="flex items-center text-green-600">
                                        <i class="fa fa-check-circle mr-2"></i>
                                        <div>
                                            <span class="font-medium">Submitted</span>
                                            <span class="block text-xs text-gray-500">
                                                {{ $existingJournal->updated_at->format('h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Journal Modal -->
<div x-data="{ show: @entangle('showJournalModal') }" x-show="show" x-cloak
class="fixed inset-0 z-50 overflow-y-auto">
<div class="flex items-center justify-center min-h-screen px-4">
    <!-- Background overlay -->
    <div x-show="show"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        @click="show = false"></div>

    <!-- Modal panel -->
    <div x-show="show"
        class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
        <!-- Modal header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                {{ $isEditing ? 'Edit Journal Entry' : 'Add Journal Entry' }}
            </h3>
        </div>

        <!-- Modal body -->
        <div class="px-6 py-4">
            <!-- Journal Text -->
            <div class="mb-4">
                <textarea wire:model.defer="journalText"
                    class="w-full min-h-[200px] p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                    placeholder="Write your tasks here..." rows="8"></textarea>
                @error('journalText')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remarks -->
            <div class="mb-4">
                <select wire:model.defer="remarks"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Status</option>
                    <option value="done">DONE</option>
                    <option value="pending">PENDING</option>
                </select>
                @error('remarks')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Modal footer -->
        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
            <button @click="show = false" type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancel
            </button>
            <button wire:click="saveJournal" type="button"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $isEditing ? 'Update Journal' : 'Save Journal' }}
            </button>
        </div>
    </div>
</div>
</div>
@endif
</div>
</div>

<!-- Keep the existing modal code -->


