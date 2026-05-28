<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LembagaController extends Controller
{
    public function index(Request $request)
    {
        $query = Lembaga::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kepala', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        $lembagaList = $query->paginate(10)->withQueryString();

        return view('super-admin.lembaga.index', compact('lembagaList'));
    }

    public function create()
    {
        return view('super-admin.lembaga.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|in:' . implode(',', Lembaga::JENIS),
            'alamat' => 'nullable|string',
            'kepala' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ], [
            'nama.required' => 'Nama lembaga wajib diisi.',
            'jenis.required' => 'Jenis lembaga wajib dipilih.',
            'kepala.required' => 'Nama kepala lembaga wajib diisi.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.max' => 'Logo maksimal berukuran 2MB.',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('lembaga', 'public');
            $validated['logo'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        Lembaga::create($validated);

        return redirect()->route('super-admin.lembaga.index')->with('success', 'Lembaga berhasil ditambahkan.');
    }

    public function show($id)
    {
        $lembaga = Lembaga::findOrFail($id);
        
        $stats = [
            'total_siswa' => $lembaga->siswa()->count(),
            'siswa_aktif' => $lembaga->totalSiswaAktif(),
            'total_guru' => $lembaga->guru()->count(),
            'guru_aktif' => $lembaga->totalGuruAktif(),
        ];

        $siswaList = $lembaga->siswa()->latest()->limit(5)->get();
        $guruList = $lembaga->guru()->latest()->limit(5)->get();

        return view('super-admin.lembaga.show', compact('lembaga', 'stats', 'siswaList', 'guruList'));
    }

    public function edit($id)
    {
        $lembaga = Lembaga::findOrFail($id);
        return view('super-admin.lembaga.edit', compact('lembaga'));
    }

    public function update(Request $request, $id)
    {
        $lembaga = Lembaga::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|in:' . implode(',', Lembaga::JENIS),
            'alamat' => 'nullable|string',
            'kepala' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ], [
            'nama.required' => 'Nama lembaga wajib diisi.',
            'jenis.required' => 'Jenis lembaga wajib dipilih.',
            'kepala.required' => 'Nama kepala lembaga wajib diisi.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.max' => 'Logo maksimal berukuran 2MB.',
        ]);

        if ($request->hasFile('logo')) {
            if ($lembaga->logo) {
                Storage::disk('public')->delete($lembaga->logo);
            }
            $path = $request->file('logo')->store('lembaga', 'public');
            $validated['logo'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $lembaga->update($validated);

        return redirect()->route('super-admin.lembaga.index')->with('success', 'Lembaga berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lembaga = Lembaga::findOrFail($id);

        if ($lembaga->users()->exists() || $lembaga->siswa()->exists() || $lembaga->guru()->exists()) {
            return back()->with('error', 'Lembaga tidak dapat dihapus karena masih memiliki data pengguna, siswa, atau guru.');
        }

        if ($lembaga->logo) {
            Storage::disk('public')->delete($lembaga->logo);
        }

        $lembaga->delete();

        return redirect()->route('super-admin.lembaga.index')->with('success', 'Lembaga berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $lembaga = Lembaga::findOrFail($id);
        $lembaga->is_active = !$lembaga->is_active;
        $lembaga->save();

        $status = $lembaga->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Lembaga {$lembaga->nama} berhasil {$status}.");
    }
}
