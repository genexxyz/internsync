<x-app-layout></x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('supervisor.dailyReports'), 'label' => 'Daily Reports']]" />
    <div class="p-6">
        <livewire:notifications/>
    </div>
</x-app-layout>