<div x-data="{ open: false }" @click.outside="open = false" class="relative w-full" wire:ignore.self>
    <div class="relative">
        @if ($multiple)
            <div class="relative">
                <div class="peer w-full min-h-[48px] pl-10 pr-4 border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-primary focus-within:border-primary flex flex-wrap gap-2 items-center pt-4 pb-2" 
                    @click="$refs.searchInput.focus(); open = true">
                    @if (!empty($selectedOptions))
                        @foreach ($selectedOptions as $selected)
                            <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm flex items-center gap-2 transition-all">
                                @php
                                    $foundOption = collect($options)->first(function ($option) use ($selected) {
                                        return is_array($option) ? ($option['value'] == $selected) : ($option == $selected);
                                    });
                                    $label = is_array($foundOption)
                                        ? $foundOption['label'] ?? ($foundOption['value'] ?? $selected)
                                        : $foundOption;
                                @endphp
                                <span>{{ $label }}</span>
                                <button type="button"
                                    wire:click.stop="removeOption('{{ $selected }}')"
                                    class="hover:text-red-500 transition-colors focus:outline-none"
                                    title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endforeach
                    @endif
                    <input 
                        x-ref="searchInput"
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        @focus="open = true"
                        placeholder=" "
                        class="outline-none bg-transparent flex-1 min-w-[120px] placeholder-gray-400 border border-gray-300 rounded-md"
                    >
                </div>

                <!-- Search Icon -->
                <i class="fa fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                
                <!-- Floating Label -->
                <label 
                    @click="$refs.searchInput.focus()"
                    class="absolute text-md text-gray-500 duration-300 transform -translate-y-5 scale-75 top-3 z-10 origin-[0] 
                    bg-white px-2 peer-focus-within:px-2 
                    peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 
                    peer-focus-within:top-3 peer-focus-within:scale-75 peer-focus-within:-translate-y-5 
                    left-9 font-medium cursor-text">
                    {{ $placeholder }}
                </label>
            </div>
        @else
            {{-- Single Selection --}}
            <div class="relative">
                <input
                    x-ref="searchInput" 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    @focus="open = true"
                    placeholder=" "
                    value="@php
                        if ($selectedOption) {
                            $foundOption = collect($options)->first(function ($option) use ($selectedOption) {
                                $value = is_array($option) ? ($option['value'] ?? $option) : $option;
                                return $value == $selectedOption;
                            });
                            echo is_array($foundOption) ? ($foundOption['label'] ?? '') : $foundOption;
                        }
                    @endphp"
                    class="peer w-full h-12 pl-10 pr-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                >
                
                <!-- Icon -->
                <i class="fa fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                
                <label 
                    @click="$refs.searchInput.focus()"
                    class="absolute text-md text-gray-500 duration-300 transform -translate-y-5 scale-75 top-3 z-10 origin-[0] 
                    bg-white px-2 peer-focus:px-2 
                    peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 
                    peer-focus:top-3 peer-focus:scale-75 peer-focus:-translate-y-5 
                    left-7 font-medium cursor-text">
                    {{ $placeholder }}
                </label>
            </div>
        @endif

        {{-- Dropdown Options --}}
        @php
            $filteredOptionsCount = is_array($filteredOptions) ? count($filteredOptions) : $filteredOptions->count();
        @endphp

        {{-- Dropdown Options --}}
        @if ($filteredOptionsCount > 0)
            <div x-show="open" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="absolute z-10 w-full mt-1 bg-white border rounded-md shadow-lg max-h-60 overflow-y-auto">
                @foreach ($filteredOptions as $option)
                    <div wire:key="{{ is_array($option) ? $option['value'] : $option }}"
                        wire:click="selectOption({{ json_encode($option) }})"
                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer transition-colors
                            {{ $multiple
                                ? (in_array(is_array($option) ? $option['value'] ?? $option : $option, $selectedOptions)
                                    ? 'bg-primary/10 text-primary'
                                    : '')
                                : ($selectedOption == (is_array($option) ? $option['value'] : $option)
                                    ? 'bg-primary/10 text-primary'
                                    : '') }}">
                        {{ is_array($option) ? $option['label'] ?? ($option['value'] ?? '') : $option }}
                    </div>
                @endforeach
            </div>
            @else
            <div>
                <p class="px-4 py-2 hover:bg-gray-100 transition-colors">No records found</p>
            </div>
        @endif
    </div>
</div>