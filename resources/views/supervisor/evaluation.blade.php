<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('supervisor.evaluation'), 'label' => 'Evaluation']]" />
    <div class="grid grid-cols-1 my-6">
        <div class="flex flex-col bg-white rounded-lg shadow-md">
            <!-- Top Section -->
            <div class="bg-secondary text-white rounded-t-lg flex items-center justify-between px-4 py-3">
                <div class="flex-grow text-start flex flex-col justify-start gap-3">
                    <p class="text-lg font-extrabold">Finished</p>


                </div>
            </div>
            <!-- Bottom Section -->
            <div class="py-3 text-center text-sm text-gray-600">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full text-sm">
                        <thead class="text-gray-600">
                            <tr class="border-b-2">
                                <th class="px-4 py-2 text-center">Name</th>
                                <th class="px-4 py-2 text-center">Position</th>
                                <th class="px-4 py-2 text-center">Date Started</th>
                                <th class="px-4 py-2 text-center">Date Ended</th>
                                <th class="px-4 py-2 text-center">Completed Hours</th>
                                <th class="px-4 py-2 text-center">Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                                <td class="py-4 px-4">Teresita Carlos</td>
                                <td class="py-4 px-4">Backend Developer</td>
                                <td class="py-4 px-4">09/10/2024</td>
                                <td class="py-4 px-4">12/16/2024</td>
                                <td class="py-4 px-4">500</td>
                                <td class="py-4 px-4">
                                    <div>
                                        <button disabled
                                            class="bg-primary text-sm py-1 px-4 rounded-full text-white shadow-md hover:shadow-none hover:bg-accent">EVALUATE</button>
                                    </div>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
