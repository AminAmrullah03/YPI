<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Lembaga;
use App\Models\Siswa;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_siswa_aktif'   => Siswa::where('status', 'aktif')->count(),
            'total_siswa'         => Siswa::count(),
            'total_guru_aktif'    => Guru::where('status', 'aktif')->count(),
            'total_guru'          => Guru::count(),
            'total_lembaga'       => Lembaga::where('is_active', true)->count(),
        ];

        // Data per lembaga untuk grafik
        $lembagaList = Lembaga::with(['siswa', 'guru'])->where('is_active', true)->get();

        $chartData = [
            'labels'       => $lembagaList->pluck('jenis_label')->toArray(),
            'siswa_aktif'  => $lembagaList->map(fn($l) => $l->siswa->where('status', 'aktif')->count())->toArray(),
            'guru_aktif'   => $lembagaList->map(fn($l) => $l->guru->where('status', 'aktif')->count())->toArray(),
        ];

        // Aktivitas terbaru
        $recentActivities = \App\Models\ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('super-admin.dashboard', compact('stats', 'chartData', 'lembagaList', 'recentActivities'));
    }
}
