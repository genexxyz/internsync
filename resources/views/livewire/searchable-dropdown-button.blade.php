<div x-data="{ open: false }" @click.outside="open = false" class="relative w-full">
    <div class="relative">
        {{-- Single Selection --}}
        <input type="text" wire:model.live.debounce.300ms="search" @focus="open = true"
            placeholder="{{ $placeholder }}"
            value="@php
if ($selectedOption) {
                $foundOption = collect($options)->first(function ($option) use ($selectedOption) {
                    $value = is_array($option) ? ($option['value'] ?? $option) : $option;
                    return $value == $selectedOption;
                });
                
                echo is_array($foundOption) 
                    ? ($foundOption['label'] ?? $foundOption['value'] ?? $selectedOption) 
                    : $foundOption;
            } @endphp"
            class="form-control block mt-1 w-full text-gray-600 border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">

        {{-- Dropdown Options --}}
        @php
            $filteredOptionsCount = is_array($filteredOptions) ? count($filteredOptions) : $filteredOptions->count();
        @endphp

        @if ($filteredOptionsCount > 0)
            <div x-show="open" x-transition
                class="absolute z-10 w-full bg-white border rounded shadow-lg max-h-60 overflow-y-auto">
                @foreach ($filteredOptions as $option)
                    <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        {{-- Main Option Label --}}
                        <div class="flex justify-between items-center">
                            <span>
                                {{ is_array($option) ? $option['label'] ?? ($option['value'] ?? '') : $option }}
                            </span>
                            {{-- Conditional Button --}}
                            @if (is_array($option) && isset($option['has_button']) && $option['has_button'])
                                <button wire:click.stop="handleButtonClick({{ json_encode($option['value']) }})"
                                    class="ml-2 bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                    Action
                                </button>
                            @endif
                        </div>
                        {{-- Option Description --}}
                        @if (is_array($option) && isset($option['description']))
                            <p class="text-sm text-gray-500">
                                {{ $option['description'] }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
