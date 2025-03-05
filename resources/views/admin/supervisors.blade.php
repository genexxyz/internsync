<x-app-layout>

    <x-breadcrumbs :breadcrumbs="[
        ['url' => route('admin.supervisors'), 'label' => 'Supervisors']
    ]" />
    


<div>

@livewire('admin.supervisor-table')


    
</div>

</x-app-layout>