<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('supervisor.dailyReports'), 'label' => 'Daily Reports']]" />



        <div class="p-6">
            <livewire:supervisor.daily-reports-table/>
        </div>
</x-app-layout>