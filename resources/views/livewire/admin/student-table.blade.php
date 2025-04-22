<div class="m-5">
    <div>

        <!-- Table -->
        <div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
            <!-- Header and Controls -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                        {{ $sectionName }} Students
                    </h2>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="fa fa-search text-gray-500"></i>
                        </span>
                        <input type="text" 
                            wire:model.live.debounce.300ms="search"
                            class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Search by name or student ID..." 
                        />
                    </div>
                    <select wire:model.live="filter"
                        class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="all">All Students</option>
                        <option value="deployed">Deployed</option>
                        <option value="completed">Completed OJT</option>
                        <option value="pending">Not Yet Deployed</option>
                    </select>
                </div>
            </div>
        
            <!-- Table Container -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                <div class="overflow-x-auto" wire:loading.class="opacity-50">
                    <!-- Loading Indicator -->
                    <div wire:loading class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-50 z-10">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
        
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Course & Section
                                </th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deployment Status
                                </th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Company
                                </th>
                                <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $student->student_id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $student->section->course->course_code }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $student->section->year_level }}-{{ $student->section->class_section }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $student->deployment ? 
                                                ($student->deployment->status === 'ONGOING' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') 
                                                : 'bg-gray-100 text-gray-800' }}">
                                            {{ $student->deployment ? $student->deployment->status : 'NOT DEPLOYED' }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->deployment?->company->company_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <button
                                            onclick="Livewire.dispatch('openModal', { 
                                                component: 'admin.student-modal', 
                                                arguments: { student: {{ $student->id }} } 
                                            })"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                        >
                                            <i class="fa fa-eye mr-2"></i>
                                            <span class="hidden sm:inline">View Details</span>
                                            <span class="sm:hidden">View</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 sm:px-6 py-4 text-center text-gray-500 text-sm">
                                        <div class="flex flex-col items-center justify-center py-6">
                                            <i class="fa fa-user-slash text-gray-400 text-3xl mb-2"></i>
                                            <span>No students found</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        
            <!-- Pagination -->
            <div class="mt-4 px-4 sm:px-0">
                {{ $students->links() }}
            </div>
        </div>
</div>