<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Exports\GuruTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $query = Guru::where('lembaga_id', $lembagaId);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nuptk', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->input('jenis_kelamin'));
        }

        $guruList = $query->latest()->paginate(15)->withQueryString();

        return view('admin.guru.index', compact('guruList'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:16',
            'nuptk' => 'nullable|string|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'jabatan' => 'required|string|max:100',
            'mata_pelajaran' => 'nullable|string|max:100',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date',
            'status_kepegawaian' => 'required|string|in:' . implode(',', array_keys(Guru::STATUS_KEPEGAWAIAN_LIST)),
            'status' => 'required|string|in:' . implode(',', array_keys(Guru::STATUS_LIST)),
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nik.required' => 'NIK guru wajib diisi.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'status_kepegawaian.required' => 'Status kepegawaian wajib dipilih.',
            'status.required' => 'Status aktif wajib dipilih.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $validated['lembaga_id'] = auth()->user()->lembaga_id;
        $validated['created_by'] = auth()->id();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('guru', 'public');
            $validated['foto'] = $path;
        }

        Guru::create($validated);

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show($id)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $guru = Guru::where('lembaga_id', $lembagaId)->findOrFail($id);
        return view('admin.guru.show', compact('guru'));
    }

    public function edit($id)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $guru = Guru::where('lembaga_id', $lembagaId)->findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $guru = Guru::where('lembaga_id', $lembagaId)->findOrFail($id);

        $validated = $request->validate([
            'nik' => 'required|string|max:16',
            'nuptk' => 'nullable|string|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'jabatan' => 'required|string|max:100',
            'mata_pelajaran' => 'nullable|string|max:100',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date',
            'status_kepegawaian' => 'required|string|in:' . implode(',', array_keys(Guru::STATUS_KEPEGAWAIAN_LIST)),
            'status' => 'required|string|in:' . implode(',', array_keys(Guru::STATUS_LIST)),
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nik.required' => 'NIK guru wajib diisi.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'status_kepegawaian.required' => 'Status kepegawaian wajib dipilih.',
            'status.required' => 'Status aktif wajib dipilih.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }
            $path = $request->file('foto')->store('guru', 'public');
            $validated['foto'] = $path;
        }

        $guru->update($validated);

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $guru = Guru::where('lembaga_id', $lembagaId)->findOrFail($id);

        if ($guru->foto) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->delete();

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $lembagaId = auth()->user()->lembaga_id;
        $guru = Guru::where('lembaga_id', $lembagaId)->findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Guru::STATUS_LIST)),
        ]);

        $guru->status = $request->input('status');
        
        // If status changed to non-active / keluar, set tanggal_keluar if not set
        if (in_array($guru->status, ['keluar', 'pensiun']) && empty($guru->tanggal_keluar)) {
            $guru->tanggal_keluar = now();
        } elseif ($guru->status === 'aktif') {
            $guru->tanggal_keluar = null;
        }

        $guru->save();

        return back()->with('success', "Status guru {$guru->nama} berhasil diubah.");
    }

    public function downloadTemplate()
    {
        return Excel::download(new GuruTemplateExport, 'template-import-guru.xlsx');
    }

    public function importForm()
    {
        return view('admin.guru.import');
    }

    public function import(Request $request)
    {
        // Check if confirmation form is submitted
        if ($request->has('confirm')) {
            $data = session()->get('guru_import_data');

            if (empty($data)) {
                return redirect()->route('admin.guru.import-form')->with('error', 'Sesi import kedaluwarsa atau kosong.');
            }

            $successCount = 0;
            $lembagaId = auth()->user()->lembaga_id;
            $userId = auth()->id();

            foreach ($data as $row) {
                Guru::create([
                    'lembaga_id' => $lembagaId,
                    'nik' => $row['nik'],
                    'nuptk' => $row['nuptk'],
                    'nama' => $row['nama'],
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'tempat_lahir' => $row['tempat_lahir'],
                    'tanggal_lahir' => $row['tanggal_lahir'],
                    'alamat' => $row['alamat'],
                    'telepon' => $row['telepon'],
                    'pendidikan_terakhir' => $row['pendidikan_terakhir'],
                    'jabatan' => $row['jabatan'],
                    'mata_pelajaran' => $row['mata_pelajaran'],
                    'tanggal_masuk' => $row['tanggal_masuk'],
                    'tanggal_keluar' => $row['tanggal_keluar'],
                    'status_kepegawaian' => $row['status_kepegawaian'],
                    'status' => $row['status'] ?? 'aktif',
                    'created_by' => $userId,
                ]);
                $successCount++;
            }

            session()->forget('guru_import_data');

            return redirect()->route('admin.guru.index')->with('success', "Berhasil mengimport {$successCount} data guru.");
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
            $nik = $row[0] ?? null;
            $nuptk = $row[1] ?? null;
            $nama = $row[2] ?? null;
            $jk = strtoupper(trim($row[3] ?? ''));
            $tempatLahir = $row[4] ?? null;
            $tglLahirRaw = $row[5] ?? null;
            $alamat = $row[6] ?? null;
            $telepon = $row[7] ?? null;
            $pendidikan = $row[8] ?? null;
            $jabatan = $row[9] ?? null;
            $mapel = $row[10] ?? null;
            $tglMasukRaw = $row[11] ?? null;
            $kepegawaianRaw = strtolower(trim($row[12] ?? ''));
            $kepegawaian = 'tidak_tetap';
            if (str_contains($kepegawaianRaw, 'tetap') && !str_contains($kepegawaianRaw, 'tidak')) {
                $kepegawaian = 'tetap';
            } elseif (str_contains($kepegawaianRaw, 'tidak') || str_contains($kepegawaianRaw, 'honorer')) {
                $kepegawaian = 'tidak_tetap';
            } elseif (str_contains($kepegawaianRaw, 'karyawan') || str_contains($kepegawaianRaw, 'magang')) {
                $kepegawaian = 'karyawan';
            }

            $status = strtolower(trim($row[13] ?? 'aktif'));

            $errors = [];

            // Validation logic
            if (empty($nik)) {
                $errors[] = 'NIK wajib diisi.';
            }

            if (empty($nama)) {
                $errors[] = 'Nama lengkap wajib diisi.';
            }

            if ($jk !== 'L' && $jk !== 'P') {
                $errors[] = "Jenis kelamin harus 'L' atau 'P' (ditemukan: '{$jk}').";
            }

            if (empty($jabatan)) {
                $errors[] = 'Jabatan wajib diisi.';
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
            if (empty($tglMasukRaw)) {
                $errors[] = 'Tanggal masuk wajib diisi.';
            } else {
                try {
                    $date = new \DateTime($tglMasukRaw);
                    $tanggalMasuk = $date->format('Y-m-d');
                } catch (\Exception $e) {
                    $errors[] = "Format tanggal masuk salah. Gunakan YYYY-MM-DD.";
                }
            }

            if (!in_array($status, array_keys(Guru::STATUS_LIST))) {
                $status = 'aktif';
            }

            $mappedRow = [
                'nik' => $nik,
                'nuptk' => $nuptk,
                'nama' => $nama,
                'jenis_kelamin' => $jk,
                'tempat_lahir' => $tempatLahir,
                'tanggal_lahir' => $tanggalLahir,
                'alamat' => $alamat,
                'telepon' => $telepon,
                'pendidikan_terakhir' => $pendidikan,
                'jabatan' => $jabatan,
                'mata_pelajaran' => $mapel,
                'tanggal_masuk' => $tanggalMasuk,
                'tanggal_keluar' => null,
                'status_kepegawaian' => $kepegawaian,
                'status' => $status,
            ];

            if (!empty($errors)) {
                $invalidRows[] = [
                    'row' => $rowNum,
                    'nama' => $nama ?? 'Guru Tanpa Nama',
                    'errors' => $errors
                ];
            } else {
                $validRows[] = $mappedRow;
            }
        }

        session()->put('guru_import_data', $validRows);

        return view('admin.guru.import_preview', compact('validRows', 'invalidRows'));
    }
}
