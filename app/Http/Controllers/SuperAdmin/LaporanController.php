<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Siswa;
use App\Models\Guru;
use App\Exports\SiswaExport;
use App\Exports\GuruExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $lembagaList = Lembaga::aktif()->get();

        // Rekap untuk dashboard laporan
        $rekap = [
            'total_siswa' => Siswa::count(),
            'siswa_aktif' => Siswa::where('status', 'aktif')->count(),
            'total_guru' => Guru::count(),
            'guru_aktif' => Guru::where('status', 'aktif')->count(),
        ];

        return view('super-admin.laporan.index', compact('lembagaList', 'rekap'));
    }

    public function exportSiswa(Request $request)
    {
        $lembagaId = $request->input('lembaga_id');
        $status = $request->input('status');
        $program = $request->input('program');
        $statusSktm = $request->input('status_sktm');

        $fileName = 'laporan-siswa';
        if ($lembagaId) {
            $lem = Lembaga::find($lembagaId);
            if ($lem) {
                $fileName .= '-' . strtolower($lem->jenis);
            }
        }
        if ($status) {
            $fileName .= '-' . $status;
        }
        if ($program) {
            $fileName .= '-' . $program;
        }
        if ($statusSktm) {
            $fileName .= '-' . $statusSktm;
        }
        $fileName .= '-' . date('YmdHis') . '.xlsx';

        return Excel::download(new SiswaExport($lembagaId, $status, $program, $statusSktm), $fileName);
    }

    public function exportGuru(Request $request)
    {
        $lembagaId = $request->input('lembaga_id');
        $status = $request->input('status');

        $fileName = 'laporan-guru';
        if ($lembagaId) {
            $lem = Lembaga::find($lembagaId);
            if ($lem) {
                $fileName .= '-' . strtolower($lem->jenis);
            }
        }
        if ($status) {
            $fileName .= '-' . $status;
        }
        $fileName .= '-' . date('YmdHis') . '.xlsx';

        return Excel::download(new GuruExport($lembagaId, $status), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $lembagaList = Lembaga::with(['siswa', 'guru'])->where('is_active', true)->get();
        
        $totalSiswa = Siswa::count();
        $siswaAktif = Siswa::where('status', 'aktif')->count();
        $totalGuru = Guru::count();
        $guruAktif = Guru::where('status', 'aktif')->count();

        $pdf = Pdf::loadView('super-admin.laporan.pdf-rekap', compact(
            'lembagaList',
            'totalSiswa',
            'siswaAktif',
            'totalGuru',
            'guruAktif'
        ));

        return $pdf->download('rekap-pendataan-ypi-' . date('Ymd') . '.pdf');
    }
}
