<div class="m-5">
    <div>
        <!-- Search and Filter -->
        <div class="flex items-center justify-between gap-4 mb-4">
            <input type="text" wire:model.live="search"
                class="border-gray-300 rounded-md shadow-sm w-full lg:w-1/3 px-3 py-2 focus:ring focus:ring-secondary"
                placeholder="Search" />
            <select wire:model.live="filter"
                class="border-gray-300 rounded-md shadow-sm px-7 py-2 focus:ring focus:ring-secondary">
                <option value="all">All</option>
                <option value="deployed">Deployed/On-going</option>
                <option value="finished">Finished</option>
                <option value="undeployed">Undeployed</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="table-auto w-full text-sm">
                <thead class="bg-secondary text-neutral-100">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2 w-1/3">Name</th>
                        <th class="px-4 py-2">Handled Section/s</th>
                        <th class="px-4 py-2">Option</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($instructors as $instructor)
                        <tr
                            class=" text-center even:bg-gray-100 odd:bg-white hover:bg-gray-400 hover:text-white border-b transition duration-200 text-neutral-800">
                            <td class="px-6 py-4 text-center">
                                {{ $loop->iteration + ($instructors->currentPage() - 1) * $instructors->perPage() }}
                            </td>
                            <td class="py-2 text-center">{{ $instructor->first_name }} {{ $instructor->last_name }}
                                {{ $instructor->suffix ?? '' }}</td>

                            <td class="px-4 py-2">
                                {{ $instructor->sections->count() ?? 0 }}
                            </td>
                            
                            <td>
                                <button
                                    class="bg-green-500 rounded-md hover:bg-green-700">
                                    <i class="fa fa-eye px-4 py-2 text-neutral-100"></i>
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">
                                No instructors found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">

            {{ $instructors->links() }}
        </div>



    </div>

</div>
