<!-- sidebar links  -->
<div class="mt-10 flex flex-col gap-2 overflow-y-auto pb-6 text-neutral-400">
    {{-- <button wire:click="triggerAlert" class="">Alert</button> --}}
    <a wire:navigate href="{{ route('student.dashboard') }}"
        class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('student.dashboard') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
        <i class="fa fa-gauge"></i>
        <span>Dashboard</span>
    </a>

    <a wire:navigate href="{{ route('student.taskAttendance') }}"
    class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
    {{ request()->routeIs('student.taskAttendance') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
    <i class="fa fa-list-check"></i>
    <span>Task & Attendance</span>
</a>
    <a wire:navigate href="{{ route('student.journey') }}"
        class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('student.journey') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
        <i class="fa fa-chart-line"></i>
        <span>OJT Journey</span>
    </a>

    

    <a wire:navigate href="{{ route('student.document') }}"
        class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
        {{ request()->routeIs('student.document') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
        <i class="fa fa-file"></i>
        <span>OJT Document</span>
    </a>





    {{-- <a href="#"
        class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('admin.documents') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
        <i class="fa fa-file"></i>
        <span>Requested Documents</span>
    </a> --}}
</div>
