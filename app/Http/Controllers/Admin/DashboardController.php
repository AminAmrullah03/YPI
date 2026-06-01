<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function index()
    {
        $lembaga   = auth()->user()->lembaga;
        $lembagaId = $lembaga?->id;

        $stats = [
            'siswa_aktif'       => Siswa::where('lembaga_id', $lembagaId)->where('status', 'aktif')->count(),
            'siswa_total'       => Siswa::where('lembaga_id', $lembagaId)->count(),
            'guru_aktif'        => Guru::where('lembaga_id', $lembagaId)->where('status', 'aktif')->count(),
            'guru_total'        => Guru::where('lembaga_id', $lembagaId)->count(),
            'siswa_tidak_aktif' => Siswa::where('lembaga_id', $lembagaId)->where('status', 'tidak_aktif')->count(),
            'guru_keluar'       => Guru::where('lembaga_id', $lembagaId)->whereIn('status', ['keluar', 'pensiun'])->count(),
            'sktm_approved'     => Siswa::where('lembaga_id', $lembagaId)->where('status_sktm', 'approved')->count(),
            'sktm_pending'      => Siswa::where('lembaga_id', $lembagaId)->where('status_sktm', 'pending')->count(),
        ];

        // Data chart: distribusi status siswa
        $statusSiswa = Siswa::where('lembaga_id', $lembagaId)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Siswa terbaru
        $siswaRecent = Siswa::where('lembaga_id', $lembagaId)->latest()->limit(5)->get();

        // Guru terbaru
        $guruRecent = Guru::where('lembaga_id', $lembagaId)->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'statusSiswa', 'siswaRecent', 'guruRecent', 'lembaga'));
    }
}
