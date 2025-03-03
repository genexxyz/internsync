
    <div class="p-6">
        @if(auth()->user()->is_verified)
            <div class="bg-white rounded-xl shadow-sm">
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Manage Interns</h2>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Section
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hours
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($deployments as $deployment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $deployment->student->first_name }} {{ $deployment->student->last_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $deployment->student->student_id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deployment->student->yearSection->course->course_code }}
                                        {{ $deployment->student->yearSection->year_level }}{{ $deployment->student->yearSection->class_section }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($deployment->starting_date)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                $deployment->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                                                ($deployment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') 
                                            }}">
                                                {{ ucfirst($deployment->status) }}
                                            </span>
                                        @else
                                            <button 
                                                wire:click="$set('selectedDeployment', {{ $deployment->id }})"
                                                class="inline-flex items-center px-3 py-1 border border-blue-600 text-xs font-medium rounded-lg text-blue-600 hover:bg-blue-50"
                                            >
                                                <i class="fas fa-calendar mr-1.5"></i>
                                                Set Start Date
                                            </button>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $deployment->hours_rendered ?? 0 }}/{{ $deployment->custom_hours ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="" 
                                            class="text-blue-600 hover:text-blue-900">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No interns found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
 <!-- Start Date Modal -->
 @if($selectedDeployment)
 <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50"></div>
 <div class="fixed inset-0 overflow-y-auto z-50">
     <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
         <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
             <div class="mb-4">
                 <h3 class="text-lg font-semibold text-gray-900">Set Internship Start Date</h3>
                 <div class="mt-4">
                     <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                     <input 
                         type="date" 
                         wire:model="startDate"
                         class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                         min="{{ now()->format('Y-m-d') }}"
                     >
                     @error('startDate')
                         <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                     @enderror
                 </div>
             </div>

             <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                 <button 
                     wire:click="saveStartDate"
                     class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-dark sm:col-start-2"
                 >
                     Save
                 </button>
                 <button 
                     wire:click="$set('selectedDeployment', null)"
                     class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0"
                 >
                     Cancel
                 </button>
             </div>
         </div>
     </div>
 </div>
@endif
                <!-- Pagination -->
                @if($deployments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $deployments->links() }}
                    </div>
                @endif
            </div>
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
