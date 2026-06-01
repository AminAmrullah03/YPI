<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SktmController extends Controller
{
    public function index()
    {
        $siswaPending = Siswa::with('lembaga')
            ->where('status_sktm', 'pending')
            ->latest()
            ->paginate(15);

        return view('super-admin.sktm.index', compact('siswaPending'));
    }

    /**
     * Sajikan berkas SKTM langsung dari controller (aman, melewati autentikasi).
     */
    public function viewBerkas($id)
    {
        $siswa = Siswa::findOrFail($id);

        if (!$siswa->dokumen_sktm || !Storage::disk('public')->exists($siswa->dokumen_sktm)) {
            abort(404, 'Berkas dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->response($siswa->dokumen_sktm);
    }

    public function approve($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->status_sktm = 'approved';
        $siswa->keterangan_sktm = null;
        $siswa->save();

        return redirect()->route('super-admin.sktm.index')->with('success', "Pengajuan SKTM untuk siswa {$siswa->nama} berhasil disetujui.");
    }

    public function reject(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'keterangan_sktm' => 'required|string|max:255',
        ], [
            'keterangan_sktm.required' => 'Catatan alasan penolakan wajib diisi.',
        ]);

        $siswa->status_sktm = 'rejected';
        $siswa->keterangan_sktm = $request->input('keterangan_sktm');
        $siswa->save();

        return redirect()->route('super-admin.sktm.index')->with('success', "Pengajuan SKTM untuk siswa {$siswa->nama} telah ditolak.");
    }
}
