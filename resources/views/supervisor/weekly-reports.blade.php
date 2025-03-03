<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('supervisor.weeklyReports'), 'label' => 'Weekly Reports']]" />



        <div class="p-6">
            <livewire:supervisor.weekly-reports-table/>
        </div>
        {{-- <div class="grid grid-cols-1 my-6">
            <div class="flex flex-col bg-white rounded-lg shadow-md">
                <!-- Top Section -->
                <div class="bg-secondary text-white rounded-t-lg flex items-center justify-between px-4 py-3">
                    <div class="flex-grow text-start flex flex-col justify-start gap-3">
                        <p class="text-lg font-extrabold">Inclusive Dates</p>
                        <div class="flex flex-row items-center gap-1">
                            <p class="text-sm">FROM</p><div class="text-gray-600">@livewire('datepicker')</div> <p class="text-sm ml-3">TO</p><div class="text-gray-600">@livewire('datepicker')</div>
                        </div>
                        
                    </div>
                </div>
                <!-- Bottom Section -->
                <div class="py-3 text-center text-sm text-gray-600">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full text-sm">
                            <thead class="text-gray-600">
                                <tr class="border-b-2">
                                    <th class="px-4 py-2 text-center">Name</th>
                                    <th class="px-4 py-2 text-center">Week No.</th>
                                    <th class="px-4 py-2 text-center">From</th>
                                    <th class="px-4 py-2 text-center">To</th>
                                    <th class="px-4 py-2 text-center">Submmitted On</th>
                                    <th class="px-4 py-2 text-center">Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    
                                    <td class="py-4 px-4">Juan Dela Cruz</td>
                                    <td class="py-4 px-4">2</td>
                                    <td class="py-4 px-4">09/16/2024</td>
                                    <td class="py-4 px-4">09/20/2024</td>
                                    <td class="py-4 px-4">09/21/2024</td>
                                    <td class="py-4 px-4">
                                        <div>
                                            <button disabled class="bg-primary text-sm py-1 px-4 rounded-full text-white shadow-md hover:shadow-none hover:bg-accent">VIEW</button>
                                        </div>
                                    </td>
                                    
                                </tr>
                                <tr>
                                    
                                    <td class="py-4 px-4">Danilo Cruz</td>
                                    <td class="py-4 px-4">2</td>
                                    <td class="py-4 px-4">09/16/2024</td>
                                    <td class="py-4 px-4">09/20/2024</td>
                                    <td class="py-4 px-4">09/21/2024</td>
                                    <td class="py-4 px-4">
                                        <div>
                                            <button disabled class="bg-primary text-sm py-1 px-4 rounded-full text-white shadow-md hover:shadow-none hover:bg-accent">VIEW</button>
                                        </div>
                                    </td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}

</x-app-layout>