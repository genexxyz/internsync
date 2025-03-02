<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('student.journey'), 'label' => 'OJT Journey']]" />

        <div class="my-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow-sm flex justify-between items-center">

            
            <div class="p-3 ml-3 text-start text-gray-600">
                
                @if($company)
                <p class="uppercase font-semibold text-xl">{{$company->company_name ?? 'Not Assigned Yet'}}</p>
                @endif
                <p class="text-sm text-gray-400">Company/Client Name</p>

            </div>
            <div>
                <i class="fa fa-building text-4xl text-gray-400 p-3"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm flex justify-between items-center">

            
            <div class="p-3 ml-3 text-start text-gray-600">
                @if($supervisor)
                <p class="uppercase font-semibold text-xl">{{ $supervisor->first_name . ' ' . $supervisor->last_name ?? 'Not Assigned'}}</p>
                @endif
                <p class="text-sm text-gray-400">Supervisor</p>
            </div>
            <div>
                <i class="fa fa-user-tie text-4xl text-gray-400 p-3"></i>
            </div>
        </div>
        </div>


    <div class="grid grid-cols-3 gap-4 my-6">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="pt-3 ml-3 text-start text-xl text-gray-600">
                <p>Starting Date</p>
            </div>

            <div class="m-3">
                <p class="border-2 border-gray-200 rounded text-sm px-3 py-2  text-center">09/10/2024</p>

            </div>
        </div>
        <div class="bg-white shadow-sm rounded-lg">
            <div class="pt-3 ml-3 text-start text-xl text-gray-600">
                <p>Total Hours Worked</p>
            </div>

            <div class="m-3">
                <div><p class="border-2 border-gray-200 rounded text-sm px-3 py-2 text-center"> 0/500</p></div>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-lg">
            <div class="pt-3 ml-3 text-start text-xl text-gray-600">
                <p>End Date</p>
            </div>

            <div class="m-3">
                
                
                <p class="border-2 border-gray-200 rounded text-sm px-3 py-2  text-center"> Not Specified</p>
            </div>
        </div>
    </div>
    <div>
        @livewire('calendar')
    </div>
</x-app-layout>
