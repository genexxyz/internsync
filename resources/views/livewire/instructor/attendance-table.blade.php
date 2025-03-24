<div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
    <!-- Header and Controls -->
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Student Attendance</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="fa fa-search text-gray-500"></i>
                </span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Search student or company" 
                />
            </div>
            <input 
                type="date" 
                wire:model.live="selectedDate"
                class="w-full sm:w-48 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                max="{{ now()->format('Y-m-d') }}"/>
        </div>
    </div>
    <div x-data="{ showJournalModal: false, studentId: null, selectedDate: @entangle('selectedDate') }">
    <!-- Table Container -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Journal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $student->first_name }} {{ $student->middle_name ? substr($student->middle_name, 0, 1) . '.' : '' }} {{ $student->last_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $student->student_id }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $student->deployment?->department?->company?->company_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $student->deployment?->department?->name }}
                                    </div>
                                </div>
                            </td>
                            <!-- Replace the time in/out cells with: -->
<td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
    @if($student->attendances && $student->attendances->first())
        {{ Carbon\Carbon::parse($student->attendances->first()->time_in)->format('h:i A') }}
    @else
        -
    @endif
</td>
<td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
    @if($student->attendances && $student->attendances->first())
        {{ Carbon\Carbon::parse($student->attendances->first()->time_out)->format('h:i A') }}
    @else
        -
    @endif
</td>
<td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
    @if($student->journals->count() > 0)
        <button 
            wire:click="viewJournal({{ $student->id }})"
            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
            <i class="fas fa-book-open mr-1"></i>
            View
        </button>
    @else
        <span class="text-xs text-gray-400">No entry</span>
    @endif
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 sm:px-6 py-4 text-center text-gray-500 text-sm">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <i class="fa fa-calendar-xmark text-gray-400 text-3xl mb-2"></i>
                                    <span>No attendance records found</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                 <!-- Journal Modal -->
                 @if($showJournalModal)
                 <div
                     class="fixed inset-0 z-50 overflow-y-auto"
                     aria-labelledby="modal-title"
                     role="dialog"
                     aria-modal="true"
                 >
                     <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
             
                     <div class="flex items-end sm:items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                         <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                             <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                 <!-- Modal Header -->
                                 <div class="flex justify-between items-center pb-4 border-b">
                                     <h3 class="text-lg font-semibold text-gray-900">
                                         Journal Entries
                                     </h3>
                                     <button 
                                         wire:click="$set('showJournalModal', false)" 
                                         class="text-gray-400 hover:text-gray-500"
                                     >
                                         <i class="fas fa-times"></i>
                                     </button>
                                 </div>
             
                                 <!-- Journal Content -->
                                 <div class="mt-4 max-h-[60vh] overflow-y-auto">
                                     @foreach($students as $student)
                                         @if($student->id === $selectedStudentId)
                                             <div class="space-y-4">
                                                 @foreach($student->journals as $journal)
                                                     <div class="bg-gray-50 rounded-lg p-4">
                                                         <p class="text-sm text-gray-900 whitespace-pre-line">
                                                             {{ $journal->text }}
                                                         </p>
                                                         <div class="mt-2 text-xs text-gray-500">
                                                             <i class="fas fa-clock mr-1"></i>
                                                             {{ Carbon\Carbon::parse($journal->updated_at)->format('h:i A') }}
                                                         </div>
                                                     </div>
                                                 @endforeach
                                             </div>
                                         @endif
                                     @endforeach
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             @endif
</div>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 px-4 sm:px-0">
        {{ $students->links() }}
    </div>
</div>