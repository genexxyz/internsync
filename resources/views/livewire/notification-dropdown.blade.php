<div x-data="{ open: @entangle('isOpen') }" class="relative" wire:poll.30s>
    <!-- Notification Button -->
    <button 
        @click="open = !open"
        class="p-2 text-white hover:bg-white/10 rounded-full transition-colors focus:outline-none"
    >
        <i class="fas fa-bell text-xl"></i>
        @if($this->unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Panel -->
    <div x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.outside="open = false"
        class="absolute right-0 mt-2 w-80 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100"
    >
        <!-- Header -->
        <div class="px-4 py-3 bg-gray-50 rounded-t-lg">
            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
        </div>

        <!-- Notification List -->
        <div class="divide-y divide-gray-100 max-h-[calc(100vh-20rem)] overflow-y-auto">
            @forelse($this->notifications as $notification)
                <a 
                    @if($notification->link)
                        href="{{ $this->resolveRoute($notification->link) }}"
                    @endif
                    wire:click="markAsRead({{ $notification->id }})"
                    @class([
                        'flex items-start gap-3 p-3 hover:bg-gray-50 transition-colors cursor-pointer',
                        'bg-blue-50/50' => !$notification->is_read
                    ])
                >
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                        <i class="fas {{ $notification->icon }} text-primary"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p @class([
                                'text-sm text-gray-900',
                                'font-semibold' => !$notification->is_read
                            ])>
                                {{ $notification->title }}
                            </p>
                            <div class="flex items-center gap-1.5">
                                <span class="inline-block w-1.5 h-1.5 rounded-full {{ !$notification->is_read ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        @if($notification->message)
                            <p class="mt-0.5 text-sm text-gray-500 line-clamp-1">
                                {{ $notification->message }}
                            </p>
                        @endif
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-sm text-gray-500">
                    No new notifications
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-2 bg-gray-50 rounded-b-lg">
            <a 
                href="{{ route('notifications.index') }}" 
                class="block w-full text-center py-2 text-sm text-primary hover:text-primary-dark font-medium rounded-lg hover:bg-gray-100"
            >
                See all notifications
            </a>
        </div>
    </div>
</div>