<!-- sidebar links -->
<div class="mt-10 flex flex-col gap-2 overflow-y-auto  text-neutral-400 h-full">

    <!-- Top Links -->
    <div>
        {{-- <button wire:click="triggerAlert" class="">Alert</button> --}}
        <a wire:navigate href="{{ route('admin.dashboard') }}"
            class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
                {{ request()->routeIs('admin.dashboard') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-gauge"></i>
            <span>Dashboard</span>
        </a>

        <a wire:navigate href="{{ route('admin.instructors') }}"
            class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
                {{ request()->routeIs('admin.instructors') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-chalkboard-user"></i>
            <span>Instructors</span>
        </a>

        <a wire:navigate href="{{ route('admin.courses') }}"
            class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('admin.courses') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-book"></i>
            <span>Courses</span>
        </a>

        <a wire:navigate href="{{ route('admin.company') }}"
            class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('admin.company') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-building"></i>
            <span>Companies</span>
        </a>
    </div>

    <!-- Bottom Section -->
    <div class="mt-auto">
        <a wire:navigate href="{{ route('admin.settings') }}"
            class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('admin.settings') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-gear"></i>
            <span>Settings</span>
        </a>
    </div>
</div>
