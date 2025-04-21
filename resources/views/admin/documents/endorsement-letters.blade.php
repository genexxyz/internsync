<x-app-layout>

    <x-breadcrumbs :breadcrumbs="[['url' => route('admin.documents.endorsement'), 'label' => 'Endorsement Letter']]" />

        @livewire('admin.documents.endorsement-table')

</x-app-layout>