<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SktmRekapController extends Controller
{
    public function index(Request $request)
    {
        // Statistik ringkasan
        $stats = [
            'total_sktm'   => Siswa::where('status_sktm', 'approved')->count(),
            'pending'      => Siswa::where('status_sktm', 'pending')->count(),
            'rejected'     => Siswa::where('status_sktm', 'rejected')->count(),
        ];

        // Rekap per lembaga
        $lembagaList = Lembaga::aktif()->get()->map(function ($lem) {
            $lem->sktm_approved = Siswa::where('lembaga_id', $lem->id)->where('status_sktm', 'approved')->count();
            $lem->sktm_pending  = Siswa::where('lembaga_id', $lem->id)->where('status_sktm', 'pending')->count();
            $lem->sktm_rejected = Siswa::where('lembaga_id', $lem->id)->where('status_sktm', 'rejected')->count();
            return $lem;
        });

        // Tabel daftar siswa SKTM dengan filter
        $query = Siswa::with('lembaga')
            ->whereIn('status_sktm', ['approved', 'pending', 'rejected']);

        if ($request->filled('lembaga_id')) {
            $query->where('lembaga_id', $request->input('lembaga_id'));
        }
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
        $allLembaga = Lembaga::aktif()->get();

        return view('super-admin.sktm.rekap', compact(
            'stats', 'lembagaList', 'siswaList', 'allLembaga'
        ));
    }

    public function viewBerkas($id)
    {
        $siswa = Siswa::findOrFail($id);

        if (!$siswa->dokumen_sktm || !Storage::disk('public')->exists($siswa->dokumen_sktm)) {
            abort(404, 'Berkas dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->response($siswa->dokumen_sktm);
    }
}
