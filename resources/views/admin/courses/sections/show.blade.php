<x-app-layout>
    
    @props(['breadcrumbs'])
    @include('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
    
    @if($section)
        <livewire:admin.student-table :section="$section" />
    @else
        <div class="flex items-center justify-center h-64">
            <div class="text-center">
                <i class="fas fa-exclamation-circle text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500">Please select a section to view students.</p>
            </div>
        </div>
    @endif
    
</x-app-layout>