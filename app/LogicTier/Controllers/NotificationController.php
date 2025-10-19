<?php

namespace App\LogicTier\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends BaseController
{
    /**
     * Ambil notifikasi admin yang belum dibaca.
     */
    public function getUnread()
    {
        $admin = Auth::guard('admin')->user();
        
        return response()->json([
            'notifications' => $admin->unreadNotifications,
            'count' => $admin->unreadNotifications->count()
        ]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAsRead()
    {
        $admin = Auth::guard('admin')->user();
        $admin->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}