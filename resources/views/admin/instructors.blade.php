<x-app-layout>

    <x-breadcrumbs :breadcrumbs="[
        ['url' => route('admin.instructors'), 'label' => 'Instructors']
    ]" />
    


<div>

@livewire('admin.instructor-table')


    
</div>

</x-app-layout>