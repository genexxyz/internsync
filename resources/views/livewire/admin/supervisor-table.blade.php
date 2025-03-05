<!-- filepath: /opt/lampp/htdocs/internsync/resources/views/livewire/admin/supervisor-table.blade.php -->
<div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
    <!-- Header and Controls -->
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Supervisors Management</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="fa fa-search text-gray-500"></i>
                </span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Search supervisors..." 
                />
            </div>
            <select 
                wire:model.live="filter"
                class="w-full sm:w-48 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            >
                <option value="all">All Supervisors</option>
                <option value="verified">Verified</option>
                <option value="unverified">Unverified</option>
            </select>
            <!-- Removed the "Add Supervisor" button -->
        </div>
    </div>

    <!-- Table Container with horizontal scroll for small screens -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <div 
            wire:loading 
            class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-50 z-10"
        >
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Company</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($supervisors as $supervisor)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $loop->iteration + ($supervisors->currentPage() - 1) * $supervisors->perPage() }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $supervisor->first_name }} {{ $supervisor->last_name }}
                                    </div>
                                    <div class="text-xs text-gray-500 truncate max-w-[200px] sm:max-w-none">
                                        {{ $supervisor->user->email ?? 'N/A' }}
                                    </div>
                                    <!-- Mobile-only company info -->
                                    <div class="sm:hidden text-xs text-gray-500 mt-1">
                                        {{ $supervisor->department->company->company_name ?? 'No Company' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                @if($supervisor->user->is_verified ?? false)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                <div class="text-sm text-gray-900">
                                    {{ $supervisor->department->company->company_name ?? 'No Company' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $supervisor->department->department_name ?? 'No Department' }}
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button
                                    onclick="Livewire.dispatch('openModal', { 
                                        component: 'admin.supervisor-modal', 
                                        arguments: { supervisor: {{ $supervisor->id }} } 
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
                                    <i class="fa fa-user-tie text-gray-400 text-3xl mb-2"></i>
                                    <span>No supervisors found</span>
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
        {{ $supervisors->links() }}
    </div>

    <!-- Modal for Edit -->
    {{-- @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <form wire:submit.prevent="save">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">
                                    Edit Supervisor
                                </h3>

                                <div class="space-y-4">
                                    <!-- First Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                                        <input type="text" wire:model="firstName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                        @error('firstName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Last Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                        <input type="text" wire:model="lastName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                        @error('lastName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Company Department - Read only -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Company & Department</label>
                                        <div class="mt-1 p-2 bg-gray-50 rounded border border-gray-300">
                                            <div class="text-sm">{{ $companyName ?? 'Not assigned' }}</div>
                                            <div class="text-xs text-gray-500">{{ $departmentName ?? 'No department' }}</div>
                                        </div>
                                    </div>

                                    <!-- Contact Number -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                                        <input type="text" wire:model="contactNumber" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                        @error('contactNumber') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-dark sm:ml-3 sm:w-auto">
                                    Update Supervisor
                                </button>
                                <button type="button" wire:click="closeModal" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}
</div>