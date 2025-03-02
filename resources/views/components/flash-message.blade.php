<div 
    x-data="{ show: true }"
    x-show="show" 
    x-init="setTimeout(() => show = false, {{ $timeout }})"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-4 right-4 px-4 py-2 rounded shadow-lg transition-all transform z-50"
    :class="{
        'bg-green-500': '{{ $type }}' === 'success',
        'bg-red-500': '{{ $type }}' === 'error',
        'bg-blue-500': '{{ $type }}' === 'info'
    }"
>
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <!-- Icon based on type -->
            <i class="mr-2 text-white" :class="{
                'fa fa-check-circle': '{{ $type }}' === 'success',
                'fa fa-times-circle': '{{ $type }}' === 'error',
                'fa fa-info-circle': '{{ $type }}' === 'info'
            }"></i>
            <span class="text-white">{{ $message }}</span>
        </div>
        <button @click="show = false" class="ml-4 text-white font-bold">X</button>
    </div>
</div>
