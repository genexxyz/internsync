<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[
        ['url' => route('admin.settings'), 'label' => 'Settings']
    ]" />

    <div>
        @livewire('admin.settings')
    </div>
</x-app-layout>