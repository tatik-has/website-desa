<?php

namespace App\LogicTier\Controllers\Shared;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Impor Auth

class NotificationController extends BaseController
{
    // ============================================================
    // METODE UNTUK USER (MASYARAKAT)
    // ============================================================

    public function index()
    {
        // Menggunakan Auth::user() untuk mengambil user yang sedang login
        $user = Auth::user(); 
        
        if (!$user) {
            // Jika entah bagaimana user tidak login, kembalikan ke halaman login
            return redirect()->route('login');
        }

        // Ambil semua notifikasi milik user tersebut
        $notifications = $user->notifications; 
        
        // Tandai notifikasi yang belum dibaca sebagai "sudah dibaca"
        // saat halaman dibuka
        $user->unreadNotifications->markAsRead();

        // Tampilkan view blade notifikasi dan kirim data notifikasinya
        return view('presentation_tier.masyarakat.notifications.index', compact('notifications'));
    }
    public function destroyAll()
    {
        // Dapatkan user yang sedang login
        $user = Auth::user();

        // Jika tidak ada user (seharusnya tidak mungkin karena middleware 'auth')
        if (!$user) {
            return redirect()->route('login');
        }

        // Hapus semua notifikasi milik user ini
        $user->notifications()->delete();

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }

    
    // ============================================================
    // METODE UNTUK ADMIN
    // ============================================================

    public function getUnread()
    {
        // PENTING: Menggunakan Auth::guard('admin') untuk mengambil admin
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
     * Dipanggil oleh rute: POST /admin/notifications/mark-as-read
     */
    public function markAsRead()
    {
        // PENTING: Menggunakan Auth::guard('admin') untuk mengambil admin
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $admin->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}