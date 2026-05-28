@extends('layouts.app')

@section('title', 'Manajemen Siswa')
@section('page-title', 'Data Siswa')

@section('content')

<div class="content-area">

    {{-- Header Actions & Filter --}}
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:24px;">
        <div style="flex:1; min-width:300px; max-width:600px;">
            <form method="GET" action="{{ route('admin.siswa.index') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="position:relative; flex:1; min-width:200px;">
                    <i class="ph ph-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:16px;"></i>
                    <input type="text" name="search" class="form-input" placeholder="Cari nama, NIS, NIK..." value="{{ request('search') }}" style="padding-left:38px;">
                </div>
                <select name="status" class="form-select" style="width:130px;">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Siswa::STATUS_LIST as $val => $label)
                        <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="jenis_kelamin" class="form-select" style="width:100px;">
                    <option value="">JK</option>
                    <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>L (Laki-laki)</option>
                    <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>P (Perempuan)</option>
                </select>
                <button type="submit" class="btn btn-primary" style="padding:10px 16px;">Filter</button>
                @if(request()->anyFilled(['search', 'status', 'jenis_kelamin']))
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary" style="padding:10px 16px;">Reset</a>
                @endif
            </form>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('admin.siswa.import-form') }}" class="btn btn-secondary" style="border:1px solid #10b981; color:#10b981; background:rgba(16,185,129,0.04);">
                <i class="ph ph-file-xls" style="font-size:16px;"></i>
                Import Excel
            </a>
            <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
                <i class="ph ph-plus-circle" style="font-size:16px;"></i>
                Tambah Siswa
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="table-wrapper">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:14px 20px; width:50px;">Foto</th>
                    <th style="padding:14px 16px;">Nama Siswa</th>
                    <th style="padding:14px 16px;">NIS / NIK</th>
                    <th style="padding:14px 16px;">Kelas</th>
                    <th style="padding:14px 16px;">JK</th>
                    <th style="padding:14px 16px;">Orang Tua / Wali</th>
                    <th style="padding:14px 16px; text-align:center; width:140px;">Status</th>
                    <th style="padding:14px 20px; text-align:center; width:150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($siswaList->isEmpty())
                    <tr>
                        <td colspan="8" style="padding:40px; text-align:center; color:#94a3b8;">
                            <i class="ph ph-student" style="font-size:40px; display:block; margin-bottom:8px;"></i>
                            Data siswa belum diinput.
                        </td>
                    </tr>
                @else
                    @foreach($siswaList as $sis)
                    <tr>
                        <td style="padding:12px 20px; text-align:center;">
                            @if($sis->foto)
                                <img src="{{ asset('storage/' . $sis->foto) }}" alt="Foto" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:1px solid #e2e8f0;">
                            @else
                                <div style="width:36px; height:36px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center; border:1px solid #e2e8f0;">
                                    <i class="ph ph-user" style="color:#94a3b8; font-size:16px;"></i>
                                </div>
                            @endif
                        </td>
                        <td style="padding:12px 16px;">
                            <div style="font-weight:700; font-size:14px; color:#1e293b;">{{ $sis->nama }}</div>
                            <div style="font-size:11px; color:#94a3b8; margin-top:2px;">Masuk: {{ $sis->tanggal_masuk?->format('d M Y') ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569;">
                            <div>NIS: <strong>{{ $sis->nis ?? '—' }}</strong></div>
                            <div style="font-size:11px; color:#94a3b8; margin-top:2px;">NIK: {{ $sis->nik ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 16px; font-size:13.5px; font-weight:600; color:#1e293b;">
                            {{ $sis->kelas ?? '—' }}
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569; font-weight:500;">
                            {{ $sis->jenis_kelamin }}
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569;">
                            <div>{{ $sis->nama_wali ?? '—' }}</div>
                            <div style="font-size:11px; color:#94a3b8; margin-top:2px;">{{ $sis->telepon_wali ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 16px; text-align:center;">
                            <form method="POST" action="{{ route('admin.siswa.status', $sis->id) }}" id="status-form-{{ $sis->id }}">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="document.getElementById('status-form-{{ $sis->id }}').submit()" style="font-size:12px; font-weight:600; padding:4px 8px; border-radius:12px; border:1px solid #e2e8f0; cursor:pointer;" class="badge badge-{{ $sis->status }}">
                                    @foreach(\App\Models\Siswa::STATUS_LIST as $val => $label)
                                        <option value="{{ $val }}" {{ $sis->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td style="padding:12px 20px; text-align:center;">
                            <div style="display:flex; justify-content:center; gap:8px;">
                                <a href="{{ route('admin.siswa.show', $sis->id) }}" class="btn btn-secondary btn-sm btn-icon" title="Detail">
                                    <i class="ph ph-eye" style="font-size:15px;"></i>
                                </a>
                                <a href="{{ route('admin.siswa.edit', $sis->id) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit" style="color:#f59e0b;">
                                    <i class="ph ph-pencil-simple" style="font-size:15px;"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.siswa.destroy', $sis->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm btn-icon" title="Hapus" style="color:#ef4444; background:none; border:1px solid #e2e8f0; cursor:pointer;">
                                        <i class="ph ph-trash" style="font-size:15px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($siswaList->hasPages())
        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $siswaList->links() }}
        </div>
    @endif

</div>

@endsection
