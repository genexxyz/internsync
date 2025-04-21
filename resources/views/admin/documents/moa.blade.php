<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('admin.documents.moa'), 'label' => 'Memorandum of Agreement']]" />

    @livewire('admin.documents.moa-table')
</x-app-layout>