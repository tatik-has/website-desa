<?php

use Illuminate\Support\Facades\Route;

// ---------------- LOGIC TIER CONTROLLERS (MASYARAKAT) ----------------
use App\LogicTier\Controllers\Masyarakat\AuthController;
use App\LogicTier\Controllers\Masyarakat\VerificationController;
use App\LogicTier\Controllers\Masyarakat\SuratController;
use App\LogicTier\Controllers\Masyarakat\DomisiliController;
use App\LogicTier\Controllers\Masyarakat\SKTMController;
use App\LogicTier\Controllers\Masyarakat\SKUController;
use App\LogicTier\Controllers\Masyarakat\ProfileController;
use App\LogicTier\Controllers\Masyarakat\MasyarakatDashboardController;
use App\LogicTier\Controllers\Masyarakat\RiwayatSuratController;

// ---------------- LOGIC TIER CONTROLLERS (ADMIN) ----------------
use App\LogicTier\Controllers\Admin\AdminAuthController;
use App\LogicTier\Controllers\Admin\AdminController;           // Fokus pada operasional surat (Update status)
use App\LogicTier\Controllers\Admin\AdminDashboardController; // Controller Baru (Statistik)
use App\LogicTier\Controllers\Admin\AdminLaporanController;   // Controller Baru (Laporan & Arsip)
use App\LogicTier\Controllers\Admin\AdminManagementController;
use App\LogicTier\Controllers\Admin\AdminProfileController;

// ---------------- SHARED CONTROLLERS ----------------
use App\LogicTier\Controllers\Shared\NotificationController;

// ---------------- DEFAULT REDIRECT ----------------
Route::get('/', function () {
    return redirect('/login');
});

// ============================================================
// USER AUTHENTICATION
// ============================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// VERIFIKASI USER
// ============================================================
Route::get('/verify', [VerificationController::class, 'showVerifyForm'])->name('verify.form');
Route::post('/verify', [VerificationController::class, 'verifyCode'])->name('verify.code');

// ============================================================
// USER DASHBOARD & PENGAJUAN SURAT
// ============================================================
Route::middleware('auth')->group(function () {
    // Dashboard utama menggunakan MasyarakatDashboardController agar SuratController fokus ke form
    Route::get('/dashboard', [MasyarakatDashboardController::class, 'index'])->name('dashboard');

    // ---------------- PENGAJUAN SURAT ----------------
    Route::get('/pengajuan', [SuratController::class, 'showPengajuanForm'])->name('pengajuan.form');
    
    Route::get('/pengajuan/domisili', [DomisiliController::class, 'showForm'])->name('pengajuan.domisili.form');
    Route::post('/pengajuan/domisili', [DomisiliController::class, 'store'])->name('pengajuan.domisili.store');

    Route::get('/pengajuan/sktm', [SKTMController::class, 'create'])->name('sktm.create');
    Route::post('/pengajuan/sktm', [SKTMController::class, 'store'])->name('sktm.store');

    Route::get('/pengajuan/sku', [SKUController::class, 'create'])->name('sku.create');
    Route::post('/pengajuan/sku', [SKUController::class, 'store'])->name('sku.store');

    // ---------------- PROFILE & HISTORY ----------------
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    // History menggunakan MasyarakatDashboardController untuk keterpisahan fungsi
    Route::get('/riwayat-surat', [MasyarakatDashboardController::class, 'history'])->name('surat.history');

    // ---------------- NOTIFIKASI ----------------
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifikasi', [NotificationController::class, 'destroyAll'])->name('notifications.deleteAll');
    Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');
    
    // ---------------- Faq ----------------
    Route::get('/faq', function () {return view('presentation_tier.masyarakat.faq.faq'); })->name('faq');

    // ---------------- Riwayat ----------------
    Route::get('/riwayat', [RiwayatSuratController::class, 'index'])->name('masyarakat.riwayat');
});

// ============================================================
// ADMIN AUTHENTICATION
// ============================================================
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ============================================================
// ADMIN DASHBOARD & MANAJEMEN SURAT
// ============================================================
Route::middleware('auth:admin')->group(function () {
    
    // Dashboard Admin (MENGGUNAKAN AdminDashboardController)
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Manajemen Surat (Fungsi operasional surat tetap di AdminController)
    Route::get('/admin/surat', [AdminController::class, 'showPermohonanSurat'])->name('admin.surat.index');
    Route::post('/admin/surat/{type}/{id}/update-status', [AdminController::class, 'updateStatusPermohonan'])->name('admin.surat.updateStatus');
    Route::get('/admin/surat/{type}/{id}/print', [AdminController::class, 'printSurat'])->name('admin.surat.print');

    // Detail Surat
    Route::get('/admin/domisili/{id}', [AdminController::class, 'showDomisiliDetail'])->name('admin.domisili.show');
    Route::get('/admin/sku/{id}', [AdminController::class, 'showSkuDetail'])->name('admin.sku.show');
    Route::get('/admin/ktm/{id}', [AdminController::class, 'showKtmDetail'])->name('admin.ktm.show');
    Route::get('/admin/sktm/{id}', [AdminController::class, 'showSktmDetail'])->name('admin.sktm.show');
    Route::get('/admin/semua-permohonan', [AdminController::class, 'semuaPermohonan'])->name('admin.semuaPermohonan');

    // Laporan & Arsip (MENGGUNAKAN AdminLaporanController)
    Route::get('/admin/laporan', [AdminLaporanController::class, 'showLaporan'])->name('admin.laporan');
    Route::get('/admin/arsip', [AdminLaporanController::class, 'showArsip'])->name('admin.arsip');
    Route::delete('/admin/surat/{type}/{id}/delete', [AdminLaporanController::class, 'destroyPermanently'])->name('admin.surat.destroy');
    
    // Fitur Arsip Aksi (Sekarang diarahkan ke AdminLaporanController karena logika arsip ada di sana)
    Route::post('/admin/surat/{type}/{id}/archive', [AdminLaporanController::class, 'archivePermohonan'])->name('admin.surat.archive');
    Route::post('/admin/run-auto-archive', [AdminLaporanController::class, 'runAutoArchive'])->name('admin.runAutoArchive');

    // Notifikasi Admin
    Route::get('/admin/notifications/unread', [NotificationController::class, 'getUnread'])->name('admin.notifications.unread');
    Route::post('/admin/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.markAsRead');

    // Manajemen Admin (Hanya Superadmin)
    Route::prefix('admin/manajemen-admin')->name('admin.manajemen-admin.')->group(function () {
        Route::get('/', [AdminManagementController::class, 'index'])->name('index');
        Route::get('/create', [AdminManagementController::class, 'create'])->name('create');
        Route::post('/', [AdminManagementController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminManagementController::class, 'destroy'])->name('destroy');
    });

    // 6. Profile Admin
    Route::get('/admin/profile', [AdminProfileController::class, 'show'])->name('admin.profile.show');
});