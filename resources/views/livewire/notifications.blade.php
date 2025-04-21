<div class="m-6">
    <div class="bg-white rounded-xl shadow-sm">
        <!-- Header -->
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Notifications</h2>
                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search notifications..." 
                            class="w-64 rounded-lg border-gray-300 pl-10 pr-4 focus:border-primary focus:ring-primary"
                        >
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    
                    <!-- Tabs -->
                    <nav class="flex gap-4">
                        <button 
                            wire:click="$set('tab', 'all')" 
                            @class([
                                'px-4 py-2 text-sm font-medium rounded-lg',
                                'bg-primary text-white' => $tab === 'all',
                                'text-gray-500 hover:text-gray-700' => $tab !== 'all'
                            ])
                        >
                            All
                        </button>
                        <button 
                            wire:click="$set('tab', 'archived')" 
                            @class([
                                'px-4 py-2 text-sm font-medium rounded-lg',
                                'bg-primary text-white' => $tab === 'archived',
                                'text-gray-500 hover:text-gray-700' => $tab !== 'archived'
                            ])
                        >
                            Archived
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Notification List -->
        <div class="divide-y divide-gray-100">
            @forelse($this->notifications as $notification)
                <div @class([
                    'p-4',
                    'bg-blue-50/50' => !$notification->is_read
                ])>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <i class="fas {{ $notification->icon }} text-primary"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 @class([
                                        'text-sm text-gray-900',
                                        'font-semibold' => !$notification->is_read
                                    ])>
                                        {{ $notification->title }}
                                    </h3>
                                    @if($notification->message)
                                        <p class="mt-0.5 text-sm text-gray-500">
                                            {{ $notification->message }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full {{ !$notification->is_read ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                        <span class="text-xs text-gray-400 whitespace-nowrap">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @if($notification->link)
                                            <a href="{{ route($notification->link) }}"
                                                class="p-1.5 text-gray-400 hover:text-gray-600 transition-colors">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                        <button 
                                            wire:click="{{ $notification->is_read ? 'markAsUnread' : 'markAsRead' }}({{ $notification->id }})"
                                            class="p-1.5 text-gray-400 hover:text-gray-600 transition-colors"
                                        >
                                            <i class="fas fa-{{ $notification->is_read ? 'envelope' : 'envelope-open' }}"></i>
                                        </button>
                                        <button 
                                            wire:click="{{ $notification->is_archived ? 'unarchive' : 'archive' }}({{ $notification->id }})"
                                            class="p-1.5 text-gray-400 hover:text-gray-600 transition-colors"
                                        >
                                            <i class="fas fa-{{ $notification->is_archived ? 'box-open' : 'archive' }}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <p class="text-gray-500">No notifications found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($this->notifications->hasPages())
            <div class="p-4 border-t">
                {{ $this->notifications->links() }}
            </div>
        @endif
    </div>
</div>