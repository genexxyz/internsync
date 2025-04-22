<!-- sidebar links -->
<div class="mt-10 flex flex-col gap-2 overflow-y-auto  text-neutral-400 h-full">

    <!-- Top Links -->
    <div>
        {{-- <button wire:click="triggerAlert" class="">Alert</button> --}}
        <a wire:navigate href="{{ route('admin.dashboard') }}" class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
                {{ request()->routeIs('admin.dashboard') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-gauge"></i>
            <span>Dashboard</span>
        </a>

        <a wire:navigate href="{{ route('admin.instructors') }}" class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
                {{ request()->routeIs('admin.instructors') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-chalkboard-user"></i>
            <span>Instructors</span>
        </a>

        <a wire:navigate href="{{ route('admin.courses') }}" class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('admin.courses') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-book"></i>
            <span>Courses</span>
        </a>

        <a wire:navigate href="{{ route('admin.company') }}" class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('admin.company') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-building"></i>
            <span>Companies</span>
        </a>
        
        <a wire:navigate href="{{ route('admin.supervisors') }}" class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
    {{ request()->routeIs('admin.supervisors') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-user-tie"></i>
            <span>Supervisors</span>
        </a>
        <div x-data="{ 
            open: localStorage.getItem('documentsOpen') === 'true' || {{ request()->routeIs('admin.documents.*') ? 'true' : 'false' }}
        }" 
            x-init="$watch('open', value => localStorage.setItem('documentsOpen', value))"
            class="relative">
            <button @click="open = !open" 
                    class="w-full flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none hover:bg-black/20
                    {{ request()->routeIs('admin.documents.*') ? 'bg-black/30 text-white' : '' }}">
                <i class="fa fa-folder"></i>
                <span>Documents</span>
                <i class="fa fa-chevron-down ml-auto text-sm transition-transform" :class="{ 'rotate-180': open }"></i>
            </button>
        
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="pl-8 mt-1 space-y-1">
                
                <a wire:navigate href="{{ route('admin.documents.acceptance') }}" 
                   class="flex items-center rounded-md gap-3 px-2 py-1.5 text-base focus:outline-none
                   {{ request()->routeIs('admin.documents.acceptance') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
                    <i class="fa fa-file-alt"></i>
                    <span>Acceptance Letters</span>
                </a>

                <a wire:navigate href="{{ route('admin.documents.endorsement') }}" 
                   class="flex items-center rounded-md gap-3 px-2 py-1.5 text-base focus:outline-none
                   {{ request()->routeIs('admin.documents.endorsement') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
                    <i class="fa fa-file-signature"></i>
                    <span>Endorsement Letters</span>
                </a>

                <a wire:navigate href="{{ route('admin.documents.moa') }}" 
                   class="flex items-center rounded-md gap-3 px-2 py-1.5 text-base focus:outline-none
                   {{ request()->routeIs('admin.documents.moa') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
                    <i class="fa fa-file-contract"></i>
                    <span>MOA</span>
                </a>
            </div>
        </div>
        <a wire:navigate href="{{ route('admin.academic-year') }}" class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
    {{ request()->routeIs('admin.academic-year') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-calendar"></i>
            <span>Academic Year</span>
        </a>
    </div>
    

    <!-- Bottom Section -->
    <div class="mt-auto">
        <a wire:navigate href="{{ route('admin.settings') }}" class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('admin.settings') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-gear"></i>
            <span>Settings</span>
        </a>
    </div>
</div>