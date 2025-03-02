<!-- Marketing link with dropdown -->
<div x-data="{ isOpen: @entangle('isOpen') }">
    <a href="#" x-on:click="isOpen = !isOpen"
        class="flex items-center rounded-md gap-4 bg-neutral-900 px-2 py-1.5 font-medium text-lg underline-offset-2 focus-visible:underline focus:outline-none ">
        <i class="fa fa-chalkboard-user"></i>
        <span>Instructors</span>
        <i class="fa fa-chevron-down ml-auto" x-bind:class="isOpen ? 'rotate-180' : ''"
            class="transition-transform"></i>
    </a>

    <!-- Dropdown menu -->
    <div x-show="isOpen" x-transition.opacity.duration.400ms
        class="mt-2 space-y-2 rounded-md border border-neutral-800 bg-neutral-900 p-2 text-neutral-400 shadow-lg">
        <a href="#"
            class="block rounded-md px-2 py-1 text-sm font-medium hover:bg-neutral-800 hover:text-white">
            Sub-item 1
        </a>
        <a href="#"
            class="block rounded-md px-2 py-1 text-sm font-medium hover:bg-neutral-800 hover:text-white">
            Sub-item 2
        </a>
        <a href="#"
            class="block rounded-md px-2 py-1 text-sm font-medium hover:bg-neutral-800 hover:text-white">
            Sub-item 3
        </a>
    </div>
</div>