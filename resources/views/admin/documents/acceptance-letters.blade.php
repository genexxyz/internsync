<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[
        ['url' => route('admin.documents.acceptance'), 'label' => 'Acceptance Letters']
    ]" />
    <livewire:admin.documents.acceptance-table />
</x-app-layout>