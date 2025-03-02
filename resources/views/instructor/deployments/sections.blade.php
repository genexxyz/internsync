<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[['url' => route('instructor.deployments.section'), 'label' => 'Deployments']]" />
    
    <livewire:instructor.deployment-section/>
</x-app-layout>