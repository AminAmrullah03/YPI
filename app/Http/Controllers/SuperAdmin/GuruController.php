<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Lembaga;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with('lembaga');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nuptk', 'like', "%{$search}%");
            });
        }

        if ($request->filled('lembaga_id')) {
            $query->where('lembaga_id', $request->input('lembaga_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->input('jenis_kelamin'));
        }

        $guruList = $query->latest()->paginate(15)->withQueryString();
        $lembagaList = Lembaga::aktif()->get();

        return view('super-admin.guru.index', compact('guruList', 'lembagaList'));
    }

    public function show($id)
    {
        $guru = Guru::with('lembaga')->findOrFail($id);
        return view('super-admin.guru.show', compact('guru'));
    }
}
