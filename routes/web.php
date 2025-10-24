<?php

use Illuminate\Support\Facades\Route;

// ---------------- LOGIC TIER CONTROLLERS ----------------
use App\LogicTier\Controllers\AuthController;
use App\LogicTier\Controllers\VerificationController;
use App\LogicTier\Controllers\SuratController;
use App\LogicTier\Controllers\DomisiliController;
use App\LogicTier\Controllers\AdminAuthController;
use App\LogicTier\Controllers\AdminController;
use App\LogicTier\Controllers\NotificationController;
use App\LogicTier\Controllers\SKTMController;
use App\LogicTier\Controllers\SKUController;


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

    Route::get('/riwayat-surat', [SuratController::class, 'history'])->name('surat.history');
    // ---------------- NOTIFIKASI ----------------
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
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

    // Data permohonan surat
    Route::get('/admin/surat', [AdminController::class, 'showPermohonanSurat'])->name('admin.surat.index');
    Route::post('/admin/surat/{type}/{id}/update-status', [AdminController::class, 'updateStatusPermohonan'])->name('admin.surat.updateStatus');

    Route::get('/admin/domisili/{permohonanDomisili}', [AdminController::class, 'showDomisiliDetail'])->name('admin.domisili.show');
    Route::get('/admin/ktm/{permohonanKTM}', [AdminController::class, 'showKtmDetail'])->name('admin.ktm.show');
    Route::get('/admin/sku/{permohonanSKU}', [AdminController::class, 'showSkuDetail'])->name('admin.sku.show');

    // Rute Notifikasi Admin (Gunakan Controller yang sama dengan User)
    Route::get('/admin/notifications/unread', [NotificationController::class, 'getUnread'])->name('admin.notifications.unread'); // <-- UBAH CLASS DI SINI
    Route::post('/admin/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.markAsRead'); // <-- UBAH CLASS DI SINI
});