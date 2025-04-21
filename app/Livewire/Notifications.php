<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    use WithPagination;

    public $tab = 'all'; // all, archived
    public $search = '';

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        $notification->update(['is_read' => true]);
    }

    public function markAsUnread($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        $notification->update(['is_read' => false]);
    }

    public function archive($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        $notification->update(['is_archived' => true]);
    }

    public function unarchive($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        $notification->update(['is_archived' => false]);
    }

    public function getNotificationsProperty()
    {
        return Notification::where('user_id', Auth::id())
            ->where('is_archived', $this->tab === 'archived')
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                      ->orWhere('message', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}