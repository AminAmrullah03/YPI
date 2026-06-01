<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SktmRekapController extends Controller
{
    public function index(Request $request)
    {
        $lembagaId = auth()->user()->lembaga_id;

        // Statistik ringkasan untuk lembaga ini
        $stats = [
            'total_sktm' => Siswa::where('lembaga_id', $lembagaId)->where('status_sktm', 'approved')->count(),
            'pending'    => Siswa::where('lembaga_id', $lembagaId)->where('status_sktm', 'pending')->count(),
            'rejected'   => Siswa::where('lembaga_id', $lembagaId)->where('status_sktm', 'rejected')->count(),
            'none'       => Siswa::where('lembaga_id', $lembagaId)->where('status_sktm', 'none')->count(),
        ];

        // Tabel daftar siswa SKTM dengan filter
        $query = Siswa::where('lembaga_id', $lembagaId)
            ->whereIn('status_sktm', ['approved', 'pending', 'rejected']);

        if ($request->filled('status_sktm')) {
            $query->where('status_sktm', $request->input('status_sktm'));
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nis', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%");
            });
        }
        if ($request->filled('program')) {
            $query->where('program', $request->input('program'));
        }

        $siswaList = $query->latest()->paginate(20)->withQueryString();

        return view('admin.sktm.rekap', compact('stats', 'siswaList'));
    }
}
