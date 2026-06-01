<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\LembagaController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\SiswaController as SuperAdminSiswaController;
use App\Http\Controllers\SuperAdmin\GuruController as SuperAdminGuruController;
use App\Http\Controllers\SuperAdmin\LaporanController as SuperAdminLaporanController;
use App\Http\Controllers\SuperAdmin\AuditLogController;
use App\Http\Controllers\SuperAdmin\SktmController as SuperAdminSktmController;
use App\Http\Controllers\SuperAdmin\SktmRekapController as SuperAdminSktmRekapController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\SiswaController as AdminSiswaController;
use App\Http\Controllers\Admin\GuruController as AdminGuruController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\SktmRekapController as AdminSktmRekapController;
use Illuminate\Support\Facades\Route;

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::get('login', [LoginController::class, 'showForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('change-password', [LoginController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('change-password', [LoginController::class, 'changePassword'])->name('password.update');
});

// ─── Redirect root ────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isSuperAdmin()
            ? redirect()->route('super-admin.dashboard')
            : redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// ─── Super Admin ──────────────────────────────────────────────────────────────
Route::prefix('super-admin')
    ->name('super-admin.')
    ->middleware(['auth', 'role:super_admin'])
    ->group(function () {

        Route::get('dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');

        // Lembaga
        Route::resource('lembaga', LembagaController::class);
        Route::patch('lembaga/{lembaga}/toggle', [LembagaController::class, 'toggleActive'])->name('lembaga.toggle');

        // User
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // Data Siswa (read-only lintas lembaga)
        Route::get('siswa', [SuperAdminSiswaController::class, 'index'])->name('siswa.index');
        Route::get('siswa/{siswa}', [SuperAdminSiswaController::class, 'show'])->name('siswa.show');

        // Data Guru (read-only lintas lembaga)
        Route::get('guru', [SuperAdminGuruController::class, 'index'])->name('guru.index');
        Route::get('guru/{guru}', [SuperAdminGuruController::class, 'show'])->name('guru.show');

        // Laporan
        Route::get('laporan', [SuperAdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export-siswa', [SuperAdminLaporanController::class, 'exportSiswa'])->name('laporan.export-siswa');
        Route::get('laporan/export-guru', [SuperAdminLaporanController::class, 'exportGuru'])->name('laporan.export-guru');
        Route::get('laporan/export-pdf', [SuperAdminLaporanController::class, 'exportPdf'])->name('laporan.export-pdf');

        // Audit Log
        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');

        // SKTM / Tidak Mampu Verification
        Route::get('sktm', [SuperAdminSktmController::class, 'index'])->name('sktm.index');
        Route::get('sktm/{siswa}/berkas', [SuperAdminSktmController::class, 'viewBerkas'])->name('sktm.berkas');
        Route::post('sktm/{siswa}/approve', [SuperAdminSktmController::class, 'approve'])->name('sktm.approve');
        Route::post('sktm/{siswa}/reject', [SuperAdminSktmController::class, 'reject'])->name('sktm.reject');

        // SKTM Rekap
        Route::get('sktm-rekap', [SuperAdminSktmRekapController::class, 'index'])->name('sktm.rekap');
    });

// ─── Admin Lembaga ────────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin_lembaga', 'lembaga_active'])
    ->group(function () {

        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Siswa
        Route::resource('siswa', AdminSiswaController::class);
        Route::patch('siswa/{siswa}/status', [AdminSiswaController::class, 'updateStatus'])->name('siswa.status');
        Route::post('siswa/{siswa}/sktm', [AdminSiswaController::class, 'uploadSktm'])->name('siswa.sktm');
        Route::get('siswa/{siswa}/sktm/berkas', [AdminSiswaController::class, 'viewSktmBerkas'])->name('siswa.sktm.berkas');
        Route::get('siswa/import/form', [AdminSiswaController::class, 'importForm'])->name('siswa.import-form');
        Route::post('siswa/import', [AdminSiswaController::class, 'import'])->name('siswa.import');
        Route::get('siswa/template/download', [AdminSiswaController::class, 'downloadTemplate'])->name('siswa.template');

        // Guru
        Route::resource('guru', AdminGuruController::class);
        Route::patch('guru/{guru}/status', [AdminGuruController::class, 'updateStatus'])->name('guru.status');
        Route::get('guru/import/form', [AdminGuruController::class, 'importForm'])->name('guru.import-form');
        Route::post('guru/import', [AdminGuruController::class, 'import'])->name('guru.import');
        Route::get('guru/template/download', [AdminGuruController::class, 'downloadTemplate'])->name('guru.template');

        // Laporan
        Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export-siswa', [AdminLaporanController::class, 'exportSiswa'])->name('laporan.export-siswa');
        Route::get('laporan/export-guru', [AdminLaporanController::class, 'exportGuru'])->name('laporan.export-guru');
        Route::get('laporan/export-pdf', [AdminLaporanController::class, 'exportPdf'])->name('laporan.export-pdf');

        // SKTM Rekap
        Route::get('sktm-rekap', [AdminSktmRekapController::class, 'index'])->name('sktm.rekap');
    });
