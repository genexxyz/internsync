<div class="grid grid-cols-1 my-6">
    <div class="flex flex-col bg-white rounded-lg shadow-md">
        <!-- Top Section -->
        <div class="bg-secondary text-white rounded-t-lg px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">Completed Internships</h2>
                    <p class="text-sm opacity-90 mt-1">Students who have completed their required hours</p>
                </div>
                <div class="bg-white/20 px-4 py-2 rounded-lg">
                    <span class="text-sm font-medium">Total: {{ $finishedStudents->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student Name
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Start Date
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Required Hours
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Completed Hours
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($finishedStudents as $deployment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $deployment->student->first_name }} {{ $deployment->student->last_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $deployment->department->department_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $deployment->starting_date->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $deployment->custom_hours }} hours
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">
                                        {{ App\Models\Attendance::getTotalApprovedHours($deployment->student_id) }} hours
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button 
                                        wire:click="$dispatch('openModal', { component: 'supervisor.evaluation-modal', arguments: { deployment: {{ $deployment->id }} }})"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white {{ $deployment->evaluation ? 'bg-secondary hover:bg-secondary-dark' : 'bg-primary hover:bg-primary-dark' }} rounded-full transition-colors duration-150"
                                    >
                                        {{ $deployment->evaluation ? 'View Evaluation' : 'Evaluate' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    No students have completed their required hours yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>