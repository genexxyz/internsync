<div x-data="{ 
    sidebarIsOpen: false,
    closeSidebarOnRoute() {
        this.sidebarIsOpen = false;
    }
}" 
x-init="
    $watch('$store.app.url', () => closeSidebarOnRoute());
    $nextTick(() => {
        window.addEventListener('navigate', () => closeSidebarOnRoute())
    });
"
class="relative flex w-full flex-col md:flex-row">
    <a class="sr-only focus:not-sr-only focus:p-4" href="#main-content">Skip to main content</a>

    <!-- Dark Overlay -->
    <div x-cloak 
    x-show="sidebarIsOpen"
    x-transition:enter="transition-opacity ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-10 bg-black/50 backdrop-blur-sm md:hidden"
    @click="sidebarIsOpen = false">
</div>

    <!-- Sidebar -->
    <nav x-cloak
    class="fixed left-0 z-20 flex h-svh w-64 shrink-0 flex-col bg-zinc-800 border-r border-r-zinc-700/50 shadow-xl md:relative
           transition-transform duration-300 ease-in-out md:translate-x-0"
    :class="sidebarIsOpen ? 'translate-x-0' : '-translate-x-64'"
    @keydown.escape.window="sidebarIsOpen = false"
    aria-label="sidebar navigation">
        
        <!-- Logo -->
        <a href="/" class="flex items-center p-4 mb-6">
            <img src="{{ asset('storage/' . $settings->default_logo)}}" 
                alt="Logo" 
                class="w-12 h-12 rounded-lg shadow-lg">
            <p class="text-white text-xl font-bold pl-3 tracking-tight">
                {{ $settings->system_name ?? 'InternSync' }}
            </p>
        </a>

        @livewire(name: Auth::user()->role . '.menu')
    </nav>

    <!-- Main Content Area -->
    <div class="h-svh w-full overflow-y-auto bg-gray-100">
        <!-- Top Navbar -->
        <nav class="sticky top-0 z-10 flex items-center justify-between bg-primary px-4 py-2 shadow-md"
            aria-label="top navigation bar">

            <!-- Mobile Header -->
            <div class="md:hidden flex items-center">
                <button type="button" 
                    class="p-2 hover:bg-white/10 rounded-lg transition-colors"
                    x-on:click="sidebarIsOpen = true">
                    <i class="fa fa-bars text-xl text-white"></i>
                    <span class="sr-only">Toggle sidebar</span>
                </button>

                <div class="flex items-center ml-4">
                    <img src="{{ asset('storage/' . $settings->default_logo) }}" 
                        alt="Logo" 
                        class="w-10 h-10 rounded-lg">
                    <p class="text-white text-lg font-semibold ml-3">
                        {{ config('app.name', 'InternSync') }}
                    </p>
                </div>
            </div>

            <!-- System Info -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Date and Time -->
                <div class="flex flex-col items-start bg-white/10 px-4 py-2 rounded-lg">
                    <p class="text-white/90 text-sm font-medium" x-data x-init="setInterval(() => $el.textContent = new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }), 1000)">
                        {{ now()->format('l, F j, Y') }}
                    </p>
                    <p class="text-white/75 text-xs" x-data x-init="setInterval(() => $el.textContent = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }), 1000)">
                        {{ now()->format('g:i A') }}
                    </p>
                </div>

                {{-- <!-- System Status -->
                <div class="flex items-center bg-white/10 px-4 py-2 rounded-lg">
                    <i class="fas fa-circle text-green-400 mr-2 text-xs animate-pulse"></i>
                    <span class="text-white/90 text-sm">System Active</span>
                </div> --}}
            </div>

            <!-- Right Side Nav Items -->
            <div class="flex items-center space-x-6">
                <!-- Notifications -->
                <div class="relative">
                    <button class="p-2 text-white hover:bg-white/10 rounded-full transition-colors focus:outline-none">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                            2
                        </span>
                    </button>
                </div>

                <!-- Profile Menu -->
                <div x-data="{ userDropdownIsOpen: false }" class="relative">
                    <button type="button"
                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white/10 transition-colors"
                        x-on:click="userDropdownIsOpen = !userDropdownIsOpen">
                        <img src="/images/default_avatar.jpg"
                            class="w-10 h-10 rounded-full object-cover shadow-md" 
                            alt="Profile photo">
                        <div class="hidden md:flex flex-col items-start">
                            <span class="text-white text-sm font-semibold">
                                {{ Auth::user()->roleInfo->first_name ?? 'N/A' }} {{ Auth::user()->roleInfo->last_name ?? 'N/A' }}
                            </span>
                            <span class="text-white/75 text-xs">{{ Auth::user()->email }}</span>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-cloak x-show="userDropdownIsOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100"
                        x-on:click.outside="userDropdownIsOpen = false">
                        
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fa fa-user w-5 h-5 text-gray-400 group-hover:text-primary"></i>
                                <span class="ml-3">Profile</span>
                            </a>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}#password" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fa fa-lock w-5 h-5 text-gray-400 group-hover:text-primary"></i>
                                <span class="ml-3">Change Password</span>
                            </a>
                        </div>

                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="group flex w-full items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                    <i class="fa fa-right-from-bracket w-5 h-5 text-red-400 group-hover:text-red-500"></i>
                                    <span class="ml-3">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main id="main-content" class="p-6">
            <div class="max-w-9xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>