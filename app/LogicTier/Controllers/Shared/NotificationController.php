<?php

namespace App\LogicTier\Controllers\Shared;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends BaseController
{
    // ============================================================
    // METODE UNTUK USER (MASYARAKAT)
    // ============================================================

    /**
     * Menampilkan daftar notifikasi masyarakat
     */
    public function index()
    {
        $user = Auth::user(); 
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Ambil semua notifikasi
        $notifications = $user->notifications; 
        
        // Otomatis tandai sebagai sudah dibaca saat membuka halaman
        $user->unreadNotifications->markAsRead();

        return view('presentation_tier.masyarakat.notifications.index', compact('notifications'));
    }

    /**
     * Menghapus SATU notifikasi tertentu
     * Perbaikan untuk error: Route [notifications.delete] not defined
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        // Cari notifikasi berdasarkan ID yang hanya dimiliki oleh user tersebut
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->delete();
            return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
    }

    /**
     * Menghapus SEMUA notifikasi milik user
     */
    public function destroyAll()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $user->notifications()->delete();

        return redirect()->back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }

    
    // ============================================================
    // METODE UNTUK ADMIN (LOGIC TIER - API/SHARED)
    // ============================================================
    public function getUnread()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        
        return response()->json([
            'notifications' => $admin->unreadNotifications,
            'count' => $admin->unreadNotifications->count()
        ]);
    }

    /**
     * [API] Tandai semua notifikasi admin sebagai sudah dibaca.
     */
    public function markAsRead()
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $admin->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}