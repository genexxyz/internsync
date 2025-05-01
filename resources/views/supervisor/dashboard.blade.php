<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[]" />
    <!-- Company Info -->
    <div class="p-6 pb-0">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 flex items-start justify-between border-b border-gray-100">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ auth()->user()->supervisor->company->company_name }}</h1>
                    <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                        <span>
                            <i class="fa fa-users mr-1.5"></i>
                            {{ auth()->user()->supervisor->department->department_name ?? 'No Department' }}
                        </span>
                        <span class="text-gray-300">•</span>
                        <span>
                            <i class="fa fa-location-dot mr-1.5"></i>
                            {{ auth()->user()->supervisor->company->address }}
                        </span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    @if(auth()->user()->supervisor->department_name)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                            Department Supervisor
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-50 text-gray-700">
                            Company Supervisor
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Stats Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6 overflow-hidden">
    <!-- Total Handled Interns -->
    <div class="rounded-xl shadow-sm bg-gradient-to-br from-violet-500 to-purple-600 hover:shadow-lg transition-all">
        <div class="px-6 py-5 flex justify-between items-center">
            <div class="text-white">
                <p class="text-5xl font-bold tracking-tight">{{$supervisor->deployments->count() ?? 0}}</p>
                <p class="mt-1 text-lg font-medium">Handled Interns</p>
                <p class="text-sm text-white/80 mt-1">Total handled interns</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg backdrop-blur-sm">
                <i class="fa fa-users text-white text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- On-going Interns -->
    <div class="rounded-xl shadow-sm bg-gradient-to-br from-blue-500 to-cyan-600 hover:shadow-lg transition-all">
        <div class="px-6 py-5">
            <div class="flex justify-between items-start mb-4">
                <div class="text-white">
                    <p class="text-5xl font-bold tracking-tight">{{ $supervisor->deployments->where('status', 'ongoing')->count() ?? 0 }}</p>
                    <p class="mt-1 text-lg font-medium">On-going</p>
                </div>
                <div class="bg-white/20 p-3 rounded-lg backdrop-blur-sm">
                    <i class="fa fa-users-between-lines text-white text-3xl"></i>
                </div>
            </div>
            <a href="{{ route('supervisor.interns') }}" class="inline-flex items-center text-sm text-white/90 hover:text-white">
                View Interns <i class="fa fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Finished Interns -->
    <div class="rounded-xl shadow-sm bg-gradient-to-br from-emerald-500 to-green-600 hover:shadow-lg transition-all">
        <div class="px-6 py-5">
            <div class="flex justify-between items-start mb-4">
                <div class="text-white">
                    <p class="text-5xl font-bold tracking-tight">{{ $supervisor->deployments->where('status', 'completed')->count() ?? 0 }}</p>
                    <p class="mt-1 text-lg font-medium">Finished</p>
                </div>
                <div class="bg-white/20 p-3 rounded-lg backdrop-blur-sm">
                    <i class="fa fa-user-check text-white text-3xl"></i>
                </div>
            </div>
            <a href="{{ route('supervisor.interns') }}" class="inline-flex items-center text-sm text-white/90 hover:text-white">
                View Interns <i class="fa fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

<!-- Notifications Section -->
<div class="p-6">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-lg font-semibold text-gray-900">Recent Notifications</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentNotifications as $notification)
                <div @class([
                    'px-6 py-4 flex items-center justify-between hover:bg-gray-50/80 transition-colors',
                    'bg-blue-50/50' => !$notification->is_read
                ])>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
                                <i class="fas {{ $notification->icon }} text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <p @class([
                                'text-sm text-gray-600',
                                'font-semibold' => !$notification->is_read
                            ])>
                                {{ $notification->message }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @if($notification->link)
                        <a href="{{ route($notification->link) }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary bg-primary/10 rounded-lg hover:bg-primary/20 transition-colors">
                            View Details
                        </a>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center">
                    <p class="text-gray-500">No recent notifications</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
</x-app-layout>