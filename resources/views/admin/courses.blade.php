<x-app-layout>
  @include('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
  

  @livewire('admin.course-cards')
  <div class="fixed bottom-4 right-4">
    <button onclick="Livewire.dispatch('openModal', { component: 'admin.add-course-modal' })"
        class="w-16 h-16 bg-blue-500 text-white rounded-full shadow-lg flex items-center justify-center hover:bg-blue-600 focus:outline-none group">
        <i class="fa fa-plus"></i>
        <!-- Tooltip -->
        <span
            class="absolute top-1/2 -translate-y-1/2 right-20 bg-gray-800 text-white text-medium px-3 py-1 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200"
            style="white-space: nowrap;">
            Add a new Course
        </span>
    </button>
</div>

</x-app-layout>
