<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Operator\OperatorDashboardController;
use App\Http\Controllers\Operator\PembayaranController;
use App\Http\Controllers\Dosen\DosenDashboardController;
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
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
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

    // Payment Management (Admin can view all)
    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
});

// Operator Routes
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [OperatorDashboardController::class, 'index'])->name('dashboard');

    // Payment Management
    Route::resource('pembayaran', PembayaranController::class)->except(['destroy']);
    Route::get('pembayaran/export', [PembayaranController::class, 'export'])->name('pembayaran.export');
    Route::post('pembayaran/{id}/verify', [PembayaranController::class, 'verify'])->name('pembayaran.verify');

    // Master Data (Read-only for Operator)
    Route::get('program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
    Route::get('program-studi/{id}', [ProgramStudiController::class, 'show'])->name('program-studi.show');

    Route::get('kurikulum', [KurikulumController::class, 'index'])->name('kurikulum.index');
    Route::get('kurikulum/{id}', [KurikulumController::class, 'show'])->name('kurikulum.show');

    Route::get('mata-kuliah', [MataKuliahController::class, 'index'])->name('mata-kuliah.index');
    Route::get('mata-kuliah/{id}', [MataKuliahController::class, 'show'])->name('mata-kuliah.show');

    Route::get('semester', [SemesterController::class, 'index'])->name('semester.index');
    Route::get('semester/{id}', [SemesterController::class, 'show'])->name('semester.show');
});

// Dosen Routes
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');

    // Schedule Management
    Route::resource('jadwal', JadwalController::class);
    Route::post('jadwal/check-conflict', [JadwalController::class, 'checkConflict'])->name('jadwal.check-conflict');

    // Grade Management
    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/create', [NilaiController::class, 'create'])->name('nilai.create');
    Route::post('nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('nilai/{id}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
    Route::put('nilai/{id}', [NilaiController::class, 'update'])->name('nilai.update');
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
});

// Mahasiswa Routes
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');

    // Student Portal
    Route::get('profile', [MahasiswaController::class, 'profile'])->name('profile');
    Route::put('profile', [MahasiswaController::class, 'updateProfile'])->name('profile.update');

    Route::get('jadwal', [MahasiswaController::class, 'jadwal'])->name('jadwal');
    Route::get('jadwal/{id}', [MahasiswaController::class, 'jadwalDetail'])->name('jadwal.detail');

    Route::get('nilai', [MahasiswaController::class, 'nilai'])->name('nilai');
    Route::get('nilai/{semester_id}', [MahasiswaController::class, 'nilaiDetail'])->name('nilai.detail');

    Route::get('khs', [MahasiswaController::class, 'khs'])->name('khs');
    Route::get('khs/{id}', [MahasiswaController::class, 'khsDetail'])->name('khs.detail');
    Route::get('khs/{id}/export', [MahasiswaController::class, 'khsExport'])->name('khs.export');

    Route::get('pembayaran', [MahasiswaController::class, 'pembayaran'])->name('pembayaran');
    Route::get('pembayaran/{id}', [MahasiswaController::class, 'pembayaranDetail'])->name('pembayaran.detail');
    Route::post('pembayaran/{id}/upload', [MahasiswaController::class, 'uploadBukti'])->name('pembayaran.upload');

    Route::get('kurikulum', [MahasiswaController::class, 'kurikulum'])->name('kurikulum');
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
