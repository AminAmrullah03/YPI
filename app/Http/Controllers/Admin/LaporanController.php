<?php

namespace App\Http\Controllers\Admin;

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
        $lembaga = auth()->user()->lembaga;
        $lembagaId = $lembaga?->id;

        $rekap = [
            'total_siswa' => Siswa::where('lembaga_id', $lembagaId)->count(),
            'siswa_aktif' => Siswa::where('lembaga_id', $lembagaId)->where('status', 'aktif')->count(),
            'total_guru' => Guru::where('lembaga_id', $lembagaId)->count(),
            'guru_aktif' => Guru::where('lembaga_id', $lembagaId)->where('status', 'aktif')->count(),
        ];

        return view('admin.laporan.index', compact('lembaga', 'rekap'));
    }

    public function exportSiswa(Request $request)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $status = $request->input('status');

        $lem = auth()->user()->lembaga;
        $fileName = 'laporan-siswa-' . strtolower($lem->jenis ?? 'lembaga');
        if ($status) {
            $fileName .= '-' . $status;
        }
        $fileName .= '-' . date('YmdHis') . '.xlsx';

        return Excel::download(new SiswaExport($lembagaId, $status), $fileName);
    }

    public function exportGuru(Request $request)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $status = $request->input('status');

        $lem = auth()->user()->lembaga;
        $fileName = 'laporan-guru-' . strtolower($lem->jenis ?? 'lembaga');
        if ($status) {
            $fileName .= '-' . $status;
        }
        $fileName .= '-' . date('YmdHis') . '.xlsx';

        return Excel::download(new GuruExport($lembagaId, $status), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $lembaga = auth()->user()->lembaga;
        $lembagaId = $lembaga?->id;

        $totalSiswa = Siswa::where('lembaga_id', $lembagaId)->count();
        $siswaAktif = Siswa::where('lembaga_id', $lembagaId)->where('status', 'aktif')->count();
        $totalGuru = Guru::where('lembaga_id', $lembagaId)->count();
        $guruAktif = Guru::where('lembaga_id', $lembagaId)->where('status', 'aktif')->count();

        $siswaList = Siswa::where('lembaga_id', $lembagaId)->orderBy('nama')->get();
        $guruList = Guru::where('lembaga_id', $lembagaId)->orderBy('nama')->get();

        $pdf = Pdf::loadView('admin.laporan.pdf-rekap', compact(
            'lembaga',
            'totalSiswa',
            'siswaAktif',
            'totalGuru',
            'guruAktif',
            'siswaList',
            'guruList'
        ));

        return $pdf->download('laporan-rekap-' . strtolower($lembaga->jenis) . '-' . date('Ymd') . '.pdf');
    }
}
