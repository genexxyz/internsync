<x-app-layout>
    @props(['breadcrumbs'])
    @include('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

    <div class="my-6">
        <div class="flex justify-start">
            <p class="font-semibold text-xl text-gray-600 ml-6">{{$course->course_code . ' ' . $section->year_level . $section->class_section}}</p>
            
        </div>
        
        <livewire:instructor.attendance-table :section_id="$section->id" />

    </div>
</x-app-layout>