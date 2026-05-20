<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($n) {
                return [
                    'title' => $n->data['title'] ?? 'Notifikasi',
                    'body'  => $n->data['body']  ?? '',
                    'link'  => $n->data['link']  ?? '#',
                    'time'  => $n->created_at->diffForHumans(),
                    'read'  => !is_null($n->read_at),
                ];
            });

        return response()->json($notifications);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
