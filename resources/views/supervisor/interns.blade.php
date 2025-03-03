<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('supervisor.interns'), 'label' => 'Interns']]" />
        <livewire:supervisor.interns-table/>
</x-app-layout>