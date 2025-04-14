<!-- sidebar links  -->
<div class="mt-10 flex flex-col gap-2 overflow-y-auto pb-6 text-neutral-400">
    {{-- <button wire:click="triggerAlert" class="">Alert</button> --}}
    <a wire:navigate href="{{ route('instructor.dashboard') }}"
        class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('instructor.dashboard') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
        <i class="fa fa-gauge"></i>
        <span>Dashboard</span>
    </a>

    <a wire:navigate href="{{ route('instructor.taskAttendance') }}"
    class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
    {{ request()->routeIs('instructor.taskAttendance') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
    <i class="fa fa-calendar-days"></i>
    <span>Task & Attendance</span>
</a>

    <a wire:navigate href="{{ route('instructor.deployments.section') }}"
        class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('instructor.deployments.section') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
        <i class="fa fa-user-check"></i>
        <span>Deployments</span>
    </a>

    @if($isCourseHead)
    <a wire:navigate href="{{ route('instructor.company') }}"
        class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
        {{ request()->routeIs('supervisor.evaluation') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
        <i class="fa fa-building"></i>
        <span>Companies</span>
    </a>
    <a wire:navigate href="{{ route('instructor.evaluation') }}"
        class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
        {{ request()->routeIs('instructor.evaluation') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
        <i class="fa fa-list-check"></i>
        <span>Evaluation</span>
    </a>
@endif





    {{-- <a href="#"
        class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
            {{ request()->routeIs('admin.documents') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
        <i class="fa fa-file"></i>
        <span>Requested Documents</span>
    </a> --}}
</div>
