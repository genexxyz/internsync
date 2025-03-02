<x-app-layout>
    
    @props(['breadcrumbs'])
    @include('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
    
    {{-- <h1>Students</h1> --}}
    
</x-app-layout>