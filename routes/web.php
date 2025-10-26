<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\NimRangeController;
use App\Http\Controllers\Admin\SPMBController as AdminSPMBController;
use App\Http\Controllers\Operator\OperatorDashboardController;
use App\Http\Controllers\Operator\PembayaranController;
use App\Http\Controllers\Operator\SPMBController as OperatorSPMBController;
use App\Http\Controllers\Dosen\DosenDashboardController;
use App\Http\Controllers\Dosen\DosenController;
use App\Http\Controllers\Dosen\JadwalController;
use App\Http\Controllers\Dosen\NilaiController;
use App\Http\Controllers\Dosen\KHSController;
use App\Http\Controllers\Mahasiswa\MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Controllers\Master\ProgramStudiController;
use App\Http\Controllers\Master\KurikulumController;
use App\Http\Controllers\Master\MataKuliahController;
use App\Http\Controllers\Master\RuanganController;
use App\Http\Controllers\Master\SemesterController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\GoogleDriveOAuthController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Public SPMB Routes (No authentication required)
Route::prefix('spmb')->name('public.spmb.')->group(function() {
    Route::get('/', [PublicController::class, 'showSPMB'])->name('index');
    Route::get('/register', [PublicController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [PublicController::class, 'storeRegistration'])->name('store');
    Route::get('/check', [PublicController::class, 'checkRegistration'])->name('check');
    Route::post('/check', [PublicController::class, 'checkRegistrationPost'])->name('check.post');
    Route::get('/result', [PublicController::class, 'showResult'])->name('result');
});

// Google Drive OAuth Routes (requires authentication)
Route::middleware('auth')->prefix('oauth/google')->name('oauth.google.')->group(function () {
    Route::get('/connect', [GoogleDriveOAuthController::class, 'redirect'])->name('connect');
    Route::get('/callback', [GoogleDriveOAuthController::class, 'callback'])->name('callback');
    Route::post('/disconnect', [GoogleDriveOAuthController::class, 'disconnect'])->name('disconnect');
    Route::get('/status', [GoogleDriveOAuthController::class, 'status'])->name('status');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', SuperAdminController::class);
    Route::post('users/{id}/restore', [SuperAdminController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [SuperAdminController::class, 'forceDelete'])->name('users.force-delete');

    // Role & Permission Management
    Route::get('permissions', [RolePermissionController::class, 'index'])->name('permissions.index');
    Route::put('permissions', [RolePermissionController::class, 'update'])->name('permissions.update');

    // Master Data Management (Admin has full access)
    Route::resource('program-studi', ProgramStudiController::class);
    Route::post('program-studi/{id}/restore', [ProgramStudiController::class, 'restore'])->name('program-studi.restore');

    Route::resource('kurikulum', KurikulumController::class);
    Route::post('kurikulum/{id}/restore', [KurikulumController::class, 'restore'])->name('kurikulum.restore');

    Route::resource('mata-kuliah', MataKuliahController::class);
    Route::post('mata-kuliah/{id}/restore', [MataKuliahController::class, 'restore'])->name('mata-kuliah.restore');

    Route::resource('ruangan', RuanganController::class);
    Route::post('ruangan/{id}/restore', [RuanganController::class, 'restore'])->name('ruangan.restore');

    Route::resource('semester', SemesterController::class);

    // Payment Management (Admin has full access)
    Route::resource('pembayaran', PembayaranController::class)->except(['destroy']);
    Route::get('pembayaran/export', [PembayaranController::class, 'export'])->name('pembayaran.export');
    Route::post('pembayaran/{id}/verify', [PembayaranController::class, 'verify'])->name('pembayaran.verify');
    Route::get('pembayaran/{id}/kwitansi', [PembayaranController::class, 'printKwitansi'])->name('pembayaran.kwitansi');
    Route::delete('pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
    Route::post('pembayaran/{id}/restore', [PembayaranController::class, 'restore'])->name('pembayaran.restore');
    Route::delete('pembayaran/{id}/force', [PembayaranController::class, 'forceDelete'])->name('pembayaran.force-delete');

    // Pengumuman Management
    Route::resource('pengumuman', \App\Http\Controllers\Admin\PengumumanController::class);

    // NIM Range Management
    Route::resource('nim-ranges', NimRangeController::class);
    Route::post('nim-ranges/bulk-create', [NimRangeController::class, 'bulkCreate'])->name('nim-ranges.bulk-create');

    // SPMB Management
    Route::prefix('spmb')->name('spmb.')->group(function() {
        Route::get('/', [AdminSPMBController::class, 'index'])->name('index');
        Route::get('/export', [AdminSPMBController::class, 'export'])->name('export');
        Route::get('/{id}', [AdminSPMBController::class, 'show'])->name('show');
        Route::post('/{id}/verify', [AdminSPMBController::class, 'verify'])->name('verify');
        Route::post('/{id}/reject', [AdminSPMBController::class, 'reject'])->name('reject');
        Route::post('/{id}/accept', [AdminSPMBController::class, 'accept'])->name('accept');
        Route::post('/bulk-verify', [AdminSPMBController::class, 'bulkVerify'])->name('bulk-verify');
        Route::post('/bulk-reject', [AdminSPMBController::class, 'bulkReject'])->name('bulk-reject');
    });

    // Pengurus Management (Dosen Wali & Ketua Prodi)
    Route::prefix('pengurus')->name('pengurus.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\PengurusController::class, 'index'])->name('index');
        Route::post('/assign-ketua-prodi', [\App\Http\Controllers\Admin\PengurusController::class, 'assignKetuaProdi'])->name('assign-ketua-prodi');
        Route::delete('/remove-ketua-prodi/{programStudiId}', [\App\Http\Controllers\Admin\PengurusController::class, 'removeKetuaProdi'])->name('remove-ketua-prodi');
        Route::get('/dosen-wali', [\App\Http\Controllers\Admin\PengurusController::class, 'dosenWali'])->name('dosen-wali');
        Route::post('/assign-dosen-wali', [\App\Http\Controllers\Admin\PengurusController::class, 'assignDosenWali'])->name('assign-dosen-wali');
        Route::post('/bulk-assign-dosen-wali', [\App\Http\Controllers\Admin\PengurusController::class, 'bulkAssignDosenWali'])->name('bulk-assign-dosen-wali');
        Route::delete('/remove-dosen-wali/{mahasiswaId}', [\App\Http\Controllers\Admin\PengurusController::class, 'removeDosenWali'])->name('remove-dosen-wali');
    });

    // Documentation
    Route::get('/docs', [AdminDashboardController::class, 'docs'])->name('docs');
});

// Operator Routes
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [OperatorDashboardController::class, 'index'])->name('dashboard');

    // Payment Management
    Route::resource('pembayaran', PembayaranController::class)->except(['destroy']);
    Route::get('pembayaran/export', [PembayaranController::class, 'export'])->name('pembayaran.export');
    Route::post('pembayaran/{id}/verify', [PembayaranController::class, 'verify'])->name('pembayaran.verify');
    Route::get('pembayaran/{id}/kwitansi', [PembayaranController::class, 'printKwitansi'])->name('pembayaran.kwitansi');
    Route::delete('pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
    Route::post('pembayaran/{id}/restore', [PembayaranController::class, 'restore'])->name('pembayaran.restore');
    Route::delete('pembayaran/{id}/force', [PembayaranController::class, 'forceDelete'])->name('pembayaran.force-delete');

    // Master Data (Read-only for Operator)
    Route::get('program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
    Route::get('program-studi/{id}', [ProgramStudiController::class, 'show'])->name('program-studi.show');

    Route::get('kurikulum', [KurikulumController::class, 'index'])->name('kurikulum.index');
    Route::get('kurikulum/{id}', [KurikulumController::class, 'show'])->name('kurikulum.show');

    Route::get('mata-kuliah', [MataKuliahController::class, 'index'])->name('mata-kuliah.index');
    Route::get('mata-kuliah/{id}', [MataKuliahController::class, 'show'])->name('mata-kuliah.show');

    Route::get('semester', [SemesterController::class, 'index'])->name('semester.index');
    Route::get('semester/{id}', [SemesterController::class, 'show'])->name('semester.show');

    // Pengumuman Management
    Route::resource('pengumuman', \App\Http\Controllers\Operator\PengumumanController::class);

    // SPMB Management (Limited for Operator)
    Route::prefix('spmb')->name('spmb.')->group(function() {
        Route::get('/', [OperatorSPMBController::class, 'index'])->name('index');
        Route::get('/export', [OperatorSPMBController::class, 'export'])->name('export');
        Route::get('/{id}', [OperatorSPMBController::class, 'show'])->name('show');
        Route::post('/{id}/verify', [OperatorSPMBController::class, 'verify'])->name('verify');
    });

    // Documentation
    Route::get('/docs', [OperatorDashboardController::class, 'docs'])->name('docs');
});

// Dosen Routes
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('profile', [DosenController::class, 'profile'])->name('profile');
    Route::put('profile', [DosenController::class, 'updateProfile'])->name('profile.update');

    // Schedule Management
    Route::resource('jadwal', JadwalController::class);
    Route::post('jadwal/check-conflict', [JadwalController::class, 'checkConflict'])->name('jadwal.check-conflict');

    // Grade Management
    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/mata-kuliah/{mataKuliahId}/mahasiswa', [NilaiController::class, 'mahasiswa'])->name('nilai.mahasiswa');
    Route::get('nilai/mata-kuliah/{mataKuliahId}/create', [NilaiController::class, 'create'])->name('nilai.create');
    Route::post('nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('nilai/{id}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
    Route::put('nilai/{id}', [NilaiController::class, 'update'])->name('nilai.update');
    Route::delete('nilai/{id}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
    Route::get('nilai/export', [NilaiController::class, 'export'])->name('nilai.export');

    // KHS Management
    Route::get('khs', [KHSController::class, 'index'])->name('khs.index');
    Route::post('khs/generate', [KHSController::class, 'generate'])->name('khs.generate');
    Route::get('khs/{id}', [KHSController::class, 'show'])->name('khs.show');
    Route::get('khs/export/{id}', [KHSController::class, 'export'])->name('khs.export');

    // Master Data (Read-only for Dosen)
    Route::get('mata-kuliah', [MataKuliahController::class, 'index'])->name('mata-kuliah.index');
    Route::get('mata-kuliah/{id}', [MataKuliahController::class, 'show'])->name('mata-kuliah.show');

    Route::get('ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::get('ruangan/{id}', [RuanganController::class, 'show'])->name('ruangan.show');

    Route::get('semester', [SemesterController::class, 'index'])->name('semester.index');
    Route::get('semester/{id}', [SemesterController::class, 'show'])->name('semester.show');

    // Pengumuman Management
    Route::resource('pengumuman', \App\Http\Controllers\Dosen\PengumumanController::class);

    // Documentation
    Route::get('/docs', [DosenDashboardController::class, 'docs'])->name('docs');
});

// Mahasiswa Routes
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');

    // Student Portal
    Route::get('profile', [MahasiswaController::class, 'profile'])->name('profile');
    Route::put('profile', [MahasiswaController::class, 'updateProfile'])->name('profile.update');

    Route::get('jadwal', [MahasiswaController::class, 'jadwal'])->name('jadwal.index');
    Route::get('jadwal/{id}', [MahasiswaController::class, 'jadwalDetail'])->name('jadwal.detail');

    Route::get('nilai', [MahasiswaController::class, 'nilai'])->name('nilai.index');
    Route::get('nilai/{semester_id}', [MahasiswaController::class, 'nilaiDetail'])->name('nilai.detail');

    Route::get('khs', [MahasiswaController::class, 'khs'])->name('khs.index');
    Route::get('khs/{id}', [MahasiswaController::class, 'khsDetail'])->name('khs.detail');
    Route::get('khs/{id}/export', [MahasiswaController::class, 'khsExport'])->name('khs.export');

    Route::get('pembayaran', [MahasiswaController::class, 'pembayaran'])->name('pembayaran.index');
    Route::get('pembayaran/{id}', [MahasiswaController::class, 'pembayaranDetail'])->name('pembayaran.detail');
    Route::post('pembayaran/{id}/upload', [MahasiswaController::class, 'uploadBukti'])->name('pembayaran.upload');

    // Notifications
    Route::get('notifications', [MahasiswaController::class, 'notifications'])->name('notifications.index');
    Route::post('notifications/{pengumumanId}/mark-read', [MahasiswaController::class, 'markNotificationAsRead'])->name('notifications.mark-read');

    Route::get('kurikulum', [MahasiswaController::class, 'kurikulum'])->name('kurikulum');

    // Documentation
    Route::get('/docs', [MahasiswaDashboardController::class, 'docs'])->name('docs');
});

// Shared Master Data Routes (accessible by multiple roles)
Route::middleware(['auth', 'role:super_admin,operator,dosen'])->prefix('master')->name('master.')->group(function () {
    // These routes allow multiple roles to access master data with appropriate permissions
    Route::get('program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
    Route::get('kurikulum', [KurikulumController::class, 'index'])->name('kurikulum.index');
    Route::get('mata-kuliah', [MataKuliahController::class, 'index'])->name('mata-kuliah.index');
    Route::get('ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::get('semester', [SemesterController::class, 'index'])->name('semester.index');
});

require __DIR__.'/auth.php';
