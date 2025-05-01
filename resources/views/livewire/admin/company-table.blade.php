<div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
    <!-- Header and Controls -->
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Companies Management</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="fa fa-search text-gray-500"></i>
                </span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Search companies..." 
                />
            </div>
        </div>
    </div>
    <div class="p-4 sm:p-6 bg-gray-50">
        <div 
            class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200"
            wire:loading.class="opacity-50"
        >
            <!-- Add a loading indicator -->
            <div 
                wire:loading 
                class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-50 z-10"
            >
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
    <!-- Table Container with horizontal scroll for small screens -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company Name</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deployed Student/s</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($companies as $company)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 sm:px-6 whitespace-nowrap text-sm text-gray-500">
                                {{ $loop->iteration + ($companies->currentPage() - 1) * $companies->perPage() }}
                            </td>
                            <td class="px-4 sm:px-6">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $company->company_name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $company->students_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-2 whitespace-nowrap text-center text-sm font-medium">
                                <button
                                    onclick="Livewire.dispatch('openModal', { 
                                        component: 'admin.company-modal', 
                                        arguments: { company: {{ $company->id }} } 
                                    })"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary hover:bg-accent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                >
                                    <i class="fa fa-eye mr-2"></i>
                                    <span class="hidden sm:inline">View Details</span>
                                    <span class="sm:hidden">View</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 sm:px-6 py-4 text-center text-gray-500 text-sm">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <i class="fa fa-building-slash text-gray-400 text-3xl mb-2"></i>
                                    <span>No companies found</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
    <!-- Pagination -->
    <div class="mt-4 px-4 sm:px-0">
        {{ $companies->links() }}
    </div>
</div>