<?php

use Illuminate\Support\Facades\Route;

// ---------------- LOGIC TIER CONTROLLERS ----------------
use App\LogicTier\Controllers\Masyarakat\AuthController;
use App\LogicTier\Controllers\Masyarakat\VerificationController;
use App\LogicTier\Controllers\Masyarakat\SuratController;
use App\LogicTier\Controllers\Masyarakat\DomisiliController;
use App\LogicTier\Controllers\Admin\AdminAuthController;
use App\LogicTier\Controllers\Admin\AdminController;
use App\LogicTier\Controllers\shared\NotificationController;
use App\LogicTier\Controllers\Masyarakat\SKTMController;
use App\LogicTier\Controllers\Masyarakat\SKUController;
use App\LogicTier\Controllers\Masyarakat\ProfileController;


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
    // Dashboard utama
    Route::get('/dashboard', [SuratController::class, 'index'])->name('dashboard');

    // ---------------- PENGAJUAN SURAT UMUM ----------------
    Route::get('/pengajuan', [SuratController::class, 'showPengajuanForm'])->name('pengajuan.form');
    Route::get('/ajukan/{jenis}', [SuratController::class, 'ajukan'])->name('ajukan.surat');

    // ---------------- PENGAJUAN SURAT DOMISILI ----------------
    Route::get('/pengajuan/domisili', [DomisiliController::class, 'showForm'])->name('pengajuan.domisili.form');
    Route::post('/pengajuan/domisili', [DomisiliController::class, 'store'])->name('pengajuan.domisili.store');

    // ---------------- PENGAJUAN SURAT SKTM / KTM ----------------
    Route::get('/pengajuan/sktm', [SKTMController::class, 'create'])->name('sktm.create');
    Route::post('/pengajuan/sktm', [SKTMController::class, 'store'])->name('sktm.store');

    // ---------------- PENGAJUAN SURAT KETERANGAN USAHA (SKU) ----------------
    Route::get('/pengajuan/sku', [SKUController::class, 'create'])->name('sku.create');
    Route::post('/pengajuan/sku', [SKUController::class, 'store'])->name('sku.store');

    // ---------------- PROFILE USER ----------------
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // ---------------- RIWAYAT PENGAJUAN ----------------
    Route::get('/riwayat-surat', [SuratController::class, 'history'])->name('surat.history');

    // ---------------- NOTIFIKASI ----------------
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifikasi', [NotificationController::class, 'destroyAll'])->name('notifications.deleteAll');
    Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');

    Route::get('/faq', function () {return view('presentation_tier.masyarakat.faq.faq');})->name('faq');
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
    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Data permohonan surat (semua jenis)
    Route::get('/admin/surat', [AdminController::class, 'showPermohonanSurat'])->name('admin.surat.index');
    Route::post('/admin/surat/{type}/{id}/update-status', [AdminController::class, 'updateStatusPermohonan'])
        ->name('admin.surat.updateStatus');

    // === DETAIL SETIAP JENIS SURAT ===
    Route::get('/admin/domisili/{id}', [AdminController::class, 'showDomisiliDetail'])->name('admin.domisili.show');
    Route::get('/admin/sku/{id}', [AdminController::class, 'showSkuDetail'])->name('admin.sku.show');
    Route::get('/admin/ktm/{id}', [AdminController::class, 'showKtmDetail'])->name('admin.ktm.show');
    Route::get('/admin/sktm/{id}', [AdminController::class, 'showSktmDetail'])->name('admin.sktm.show'); // tambahan

    // === SEMUA PERMOHONAN (gabungan) ===
    Route::get('/admin/semua-permohonan', [AdminController::class, 'semuaPermohonan'])->name('admin.semuaPermohonan');

    // === NOTIFIKASI ADMIN ===
    Route::get('/admin/notifications/unread', [NotificationController::class, 'getUnread'])
        ->name('admin.notifications.unread');
    Route::post('/admin/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])
        ->name('admin.notifications.markAsRead');

    // === Laporan ===
    Route::get('/admin/laporan', [\App\LogicTier\Controllers\Admin\AdminController::class, 'showLaporan'])
        ->name('admin.laporan');
    
    // === MANAJEMEN ADMIN (hanya untuk superadmin) ===
    Route::prefix('admin/manajemen-admin')->name('admin.manajemen-admin.')->group(function () {
        Route::get('/', [\App\LogicTier\Controllers\Admin\AdminManagementController::class, 'index'])->name('index');
        Route::get('/create', [\App\LogicTier\Controllers\Admin\AdminManagementController::class, 'create'])->name('create');
        Route::post('/', [\App\LogicTier\Controllers\Admin\AdminManagementController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\LogicTier\Controllers\Admin\AdminManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\LogicTier\Controllers\Admin\AdminManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\LogicTier\Controllers\Admin\AdminManagementController::class, 'destroy'])->name('destroy');
    
    });

    Route::get('/admin/profile', [App\LogicTier\Controllers\Admin\AdminProfileController::class, 'show'])
         ->name('admin.profile.show'); // <-- Ini yang dicari
         
    Route::post('/admin/profile', [App\LogicTier\Controllers\Admin\AdminProfileController::class, 'update'])
         ->name('admin.profile.update');
});