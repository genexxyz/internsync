<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'icon',
        'is_read',
        'is_archived',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
    public function Unarchived($query)
    {
        return $query->where('is_archived', false);
    }

    public static function send($userId, $type, $title, $message = null, $link = null, $icon = null)
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'icon' => $icon ?? 'fa-bell', // Default icon if none provided
            'is_read' => false,
            'is_archived' => false,
        ]);
    }

    // Example usage:
    // Notification::send(
    //     $user->id,
    //     'task_approved',
    //     'Task Approved',
    //     'Your task has been approved by your supervisor',
    //     route('student.tasks'),
    //     'fa-check-circle'
    // );
}
