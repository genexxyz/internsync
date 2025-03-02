@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidth = [
    'xs' => 'sm:max-w-xs',
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
            return [...$el.querySelectorAll(selector)]
                .filter(el => !el.hasAttribute('disabled'));
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % this.focusables().length },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement) - 1) },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable().focus(), 100)' : '' }}
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0"
    style="display: {{ $show ? 'flex' : 'none' }};"
>
    <!-- Background overlay -->
    <div
        x-show="show"
        class="fixed inset-0 bg-gray-800 bg-opacity-75 transition-opacity"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <!-- Modal content -->
    <div
        x-show="show"
        class="relative bg-white rounded-lg shadow-xl transform transition-all w-full {{ $maxWidth }} sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <!-- Close button (optional) -->
        <button
            type="button"
            x-on:click="show = false"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none"
        >
            <span class="sr-only">Close</span>
            <i class="fa fa-times"></i>
        </button>

        <!-- Slot for modal content -->
        <div class="px-6 py-4">
            {{ $slot }}
        </div>

        <!-- Footer (optional) -->
        @isset($footer)
            <div class="px-6 py-4 bg-gray-100 text-right">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
