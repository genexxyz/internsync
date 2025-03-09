<!-- sidebar links  -->
<div>
   
    
        <div class="mt-10 flex flex-col gap-2 overflow-y-auto pb-6 text-neutral-400">
            {{-- <button wire:click="triggerAlert" class="">Alert</button> --}}
            <a wire:navigate href="{{ route('supervisor.dashboard') }}"
                class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
                    {{ request()->routeIs('supervisor.dashboard') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
                <i class="fa fa-gauge"></i>
                <span>Dashboard</span>
            </a>
        
            <a wire:navigate href="{{ route('supervisor.interns') }}"
            class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
                {{ request()->routeIs('supervisor.interns') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
            <i class="fa fa-users"></i>
            <span>Interns</span>
        </a>
            <a wire:navigate href="{{ route('supervisor.weeklyReports') }}"
                class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
                    {{ request()->routeIs('supervisor.weeklyReports') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
                <i class="fa fa-chart-line"></i>
                <span>Weekly Reports</span>
            </a>
        
            <a wire:navigate href="{{ route('supervisor.evaluation') }}"
                class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
                {{ request()->routeIs('supervisor.evaluation') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
                <i class="fa fa-list-check"></i>
                <span>Evaluation</span>
            </a>
        
        
        
        
        
        
            {{-- <a href="#"
                class="flex items-center rounded-md gap-3 px-2 py-1.5 font-medium text-lg underline-offset-2 focus:outline-none
                    {{ request()->routeIs('admin.documents') ? 'bg-black/30 text-white' : 'hover:bg-black/20' }}">
                <i class="fa fa-file"></i>
                <span>Requested Documents</span>
            </a> --}}
        </div>
    
    

</div>