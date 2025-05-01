@props(['disabled' => false, 'placeholder' => ''])

<div class="relative" x-data>
    <input 
        @disabled($disabled) 
        placeholder=" "
        x-ref="input"
        {{ $attributes->merge(['class' => 'peer border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm w-full h-12']) }}
    >
    
    <!-- Floating Label -->
    <label 
        @click="$refs.input.focus()"
        class="absolute text-md text-gray-500 duration-300 transform -translate-y-5 scale-75 top-3 z-9 origin-[0] 
        bg-white px-2 peer-focus:px-2 
        peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 
        peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-5 
        left-1 font-medium cursor-text">
        {{ $placeholder }}
    </label>
</div>