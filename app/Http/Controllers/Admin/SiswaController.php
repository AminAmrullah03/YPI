<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Exports\SiswaTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $query = Siswa::where('lembaga_id', $lembagaId);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->input('jenis_kelamin'));
        }

        $siswaList = $query->latest()->paginate(15)->withQueryString();

        return view('admin.siswa.index', compact('siswaList'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'nama_wali' => 'nullable|string|max:255',
            'telepon_wali' => 'nullable|string|max:20',
            'tanggal_masuk' => 'nullable|date',
            'kelas' => 'nullable|string|max:50',
            'status' => 'required|string|in:' . implode(',', array_keys(Siswa::STATUS_LIST)),
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama.required' => 'Nama siswa wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status.required' => 'Status siswa wajib dipilih.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $validated['lembaga_id'] = auth()->user()->lembaga_id;
        $validated['created_by'] = auth()->id();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('siswa', 'public');
            $validated['foto'] = $path;
        }

        Siswa::create($validated);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show($id)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $siswa = Siswa::where('lembaga_id', $lembagaId)->findOrFail($id);
        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit($id)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $siswa = Siswa::where('lembaga_id', $lembagaId)->findOrFail($id);
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $siswa = Siswa::where('lembaga_id', $lembagaId)->findOrFail($id);

        $validated = $request->validate([
            'nis' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'nama_wali' => 'nullable|string|max:255',
            'telepon_wali' => 'nullable|string|max:20',
            'tanggal_masuk' => 'nullable|date',
            'kelas' => 'nullable|string|max:50',
            'status' => 'required|string|in:' . implode(',', array_keys(Siswa::STATUS_LIST)),
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama.required' => 'Nama siswa wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status.required' => 'Status siswa wajib dipilih.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $path = $request->file('foto')->store('siswa', 'public');
            $validated['foto'] = $path;
        }

        $siswa->update($validated);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $siswa = Siswa::where('lembaga_id', $lembagaId)->findOrFail($id);

        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $siswa = Siswa::where('lembaga_id', $lembagaId)->findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Siswa::STATUS_LIST)),
        ]);

        $siswa->status = $request->input('status');
        $siswa->save();

        return back()->with('success', "Status siswa {$siswa->nama} berhasil diubah.");
    }

    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport, 'template-import-siswa.xlsx');
    }

    public function importForm()
    {
        return view('admin.siswa.import');
    }

    public function import(Request $request)
    {
        // Check if confirmation form is submitted
        if ($request->has('confirm')) {
            $data = session()->get('siswa_import_data');

            if (empty($data)) {
                return redirect()->route('admin.siswa.import-form')->with('error', 'Sesi import kedaluwarsa atau kosong.');
            }

            $successCount = 0;
            $lembagaId = auth()->user()->lembaga_id;
            $userId = auth()->id();

            foreach ($data as $row) {
                Siswa::create([
                    'lembaga_id' => $lembagaId,
                    'nis' => $row['nis'],
                    'nik' => $row['nik'],
                    'nama' => $row['nama'],
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'tempat_lahir' => $row['tempat_lahir'],
                    'tanggal_lahir' => $row['tanggal_lahir'],
                    'alamat' => $row['alamat'],
                    'nama_wali' => $row['nama_wali'],
                    'telepon_wali' => $row['telepon_wali'],
                    'tanggal_masuk' => $row['tanggal_masuk'],
                    'kelas' => $row['kelas'],
                    'status' => $row['status'] ?? 'aktif',
                    'created_by' => $userId,
                ]);
                $successCount++;
            }

            session()->forget('siswa_import_data');

            return redirect()->route('admin.siswa.index')->with('success', "Berhasil mengimport {$successCount} data siswa.");
        }

        // Upload and parse flow
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file.required' => 'File Excel wajib diunggah.',
            'file.mimes' => 'File harus bertipe Excel (.xlsx, .xls, .csv).'
        ]);

        $rows = Excel::toArray(new \stdClass, $request->file('file'))[0];
        
        // Remove header row
        $header = array_shift($rows);

        $validRows = [];
        $invalidRows = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // header is row 1
            
            // Map row indices to variables
            $nis = $row[0] ?? null;
            $nik = $row[1] ?? null;
            $nama = $row[2] ?? null;
            $jk = strtoupper(trim($row[3] ?? ''));
            $tempatLahir = $row[4] ?? null;
            $tglLahirRaw = $row[5] ?? null;
            $alamat = $row[6] ?? null;
            $namaWali = $row[7] ?? null;
            $telpWali = $row[8] ?? null;
            $tglMasukRaw = $row[9] ?? null;
            $kelas = $row[10] ?? null;
            $status = strtolower(trim($row[11] ?? 'aktif'));

            $errors = [];

            // Validation logic
            if (empty($nama)) {
                $errors[] = 'Nama lengkap wajib diisi.';
            }

            if ($jk !== 'L' && $jk !== 'P') {
                $errors[] = "Jenis kelamin harus 'L' atau 'P' (ditemukan: '{$jk}').";
            }

            // Parse & validate birth date
            $tanggalLahir = null;
            if (!empty($tglLahirRaw)) {
                try {
                    $date = new \DateTime($tglLahirRaw);
                    $tanggalLahir = $date->format('Y-m-d');
                } catch (\Exception $e) {
                    $errors[] = "Format tanggal lahir salah. Gunakan YYYY-MM-DD.";
                }
            }

            // Parse & validate admission date
            $tanggalMasuk = null;
            if (!empty($tglMasukRaw)) {
                try {
                    $date = new \DateTime($tglMasukRaw);
                    $tanggalMasuk = $date->format('Y-m-d');
                } catch (\Exception $e) {
                    $errors[] = "Format tanggal masuk salah. Gunakan YYYY-MM-DD.";
                }
            }

            if (!in_array($status, array_keys(Siswa::STATUS_LIST))) {
                $status = 'aktif';
            }

            $mappedRow = [
                'nis' => $nis,
                'nik' => $nik,
                'nama' => $nama,
                'jenis_kelamin' => $jk,
                'tempat_lahir' => $tempatLahir,
                'tanggal_lahir' => $tanggalLahir,
                'alamat' => $alamat,
                'nama_wali' => $namaWali,
                'telepon_wali' => $telpWali,
                'tanggal_masuk' => $tanggalMasuk,
                'kelas' => $kelas,
                'status' => $status,
            ];

            if (!empty($errors)) {
                $invalidRows[] = [
                    'row' => $rowNum,
                    'nama' => $nama ?? 'Siswa Tanpa Nama',
                    'errors' => $errors
                ];
            } else {
                $validRows[] = $mappedRow;
            }
        }

        session()->put('siswa_import_data', $validRows);

        return view('admin.siswa.import_preview', compact('validRows', 'invalidRows'));
    }
}
