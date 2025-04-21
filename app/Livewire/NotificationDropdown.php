<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationDropdown extends Component
{
    public $isOpen = false;
    
    public function getNotificationsProperty()
    {
        return Notification::where('user_id', Auth::id())
            ->where('is_archived', false)
            ->latest()
            ->take(3)
            ->get();
    }

    public function getUnreadCountProperty()
    {
        return Notification::where('user_id', Auth::id())
            ->where('is_archived', false)
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->update(['is_read' => true]);
        }
    }
    public function resolveRoute($link)
    {
        if (empty($link)) {
            return '#';
        }

        // Check if the route exists
        try {
            return route($link);
        } catch (\Exception $e) {
            // Fallback to dashboard if route doesn't exist
            return route('login');
        }
    }

    public function render()
    {
        return view('livewire.notification-dropdown');
    }
}