@props([
    'options' => [],
    'selected' => null,
    'name' => '',
    'class' => '',
    'placeholder' => 'Select an option',
    'icon' => '',
])

<div class="relative">
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        wire:model.live="{{ $name }}"
        class="peer w-full h-12 pl-10 pr-4 border border-gray-300 rounded-md focus:border-primary focus:ring-primary focus:ring-1 appearance-none cursor-pointer {{ $class }}"
        required
    >
        <option value="" selected></option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" {{ $value == old($name, $selected) ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>

    <!-- Floating Label -->
    <label 
        for="{{ $name }}"
        class="absolute text-md text-gray-500 duration-300 transform 
        bg-white px-2
        peer-focus:-translate-y-5 peer-focus:scale-75 peer-focus:top-3 
        peer-focus:z-10 peer-focus:px-2 
        peer-valid:-translate-y-5 peer-valid:scale-75 peer-valid:top-3
        left-7 font-medium cursor-pointer
        {{ !$selected ? 'top-1/2 -translate-y-1/2' : '-translate-y-5 scale-75 top-3 z-10' }}">
        {{ $placeholder }}
    </label>

    <!-- Icon -->
    @if($icon)
        <i class="{{ $icon }} absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
    @endif

    
</div>