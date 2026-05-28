@extends('layouts.app')

@section('title', 'Manajemen Lembaga')
@section('page-title', 'Manajemen Lembaga')

@section('content')

<div class="content-area">

    {{-- Header Actions & Filter --}}
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:24px;">
        <div style="flex:1; min-width:300px; max-width:500px;">
            <form method="GET" action="{{ route('super-admin.lembaga.index') }}" style="display:flex; gap:10px;">
                <div style="position:relative; flex:1;">
                    <i class="ph ph-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:16px;"></i>
                    <input type="text" name="search" class="form-input" placeholder="Cari nama lembaga atau kepala..." value="{{ request('search') }}" style="padding-left:38px;">
                </div>
                <select name="jenis" class="form-select" style="width:140px;">
                    <option value="">Semua Jenis</option>
                    @foreach(\App\Models\Lembaga::JENIS as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                            {{ \App\Models\Lembaga::JENIS_LABEL[$jenis] }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary" style="padding:10px 16px;">Filter</button>
                @if(request()->anyFilled(['search', 'jenis']))
                    <a href="{{ route('super-admin.lembaga.index') }}" class="btn btn-secondary" style="padding:10px 16px;">Reset</a>
                @endif
            </form>
        </div>
        <div>
            <a href="{{ route('super-admin.lembaga.create') }}" class="btn btn-primary">
                <i class="ph ph-plus-circle" style="font-size:16px;"></i>
                Tambah Lembaga
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="table-wrapper">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:14px 20px; width:60px;">Logo</th>
                    <th style="padding:14px 16px;">Nama Lembaga</th>
                    <th style="padding:14px 16px;">Jenis</th>
                    <th style="padding:14px 16px;">Kepala Lembaga</th>
                    <th style="padding:14px 16px;">Hubungi</th>
                    <th style="padding:14px 16px; text-align:center;">Status</th>
                    <th style="padding:14px 20px; text-align:center; width:200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($lembagaList->isEmpty())
                    <tr>
                        <td colspan="7" style="padding:40px; text-align:center; color:#94a3b8;">
                            <i class="ph ph-folder-open" style="font-size:40px; display:block; margin-bottom:8px;"></i>
                            Data lembaga tidak ditemukan.
                        </td>
                    </tr>
                @else
                    @foreach($lembagaList as $lem)
                    <tr>
                        <td style="padding:12px 20px; text-align:center;">
                            @if($lem->logo)
                                <img src="{{ asset('storage/' . $lem->logo) }}" alt="Logo" style="width:40px; height:40px; border-radius:8px; object-fit:cover; border:1px solid #e2e8f0;">
                            @else
                                <div style="width:40px; height:40px; background:linear-gradient(135deg, #10b981, #059669); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:14px;">
                                    {{ substr($lem->nama, 0, 2) }}
                                </div>
                            @endif
                        </td>
                        <td style="padding:12px 16px;">
                            <div style="font-weight:700; font-size:14px; color:#1e293b;">{{ $lem->nama }}</div>
                            <div style="font-size:12px; color:#94a3b8; margin-top:2px;">
                                <i class="ph ph-map-pin" style="font-size:11px; margin-right:2px;"></i>
                                {{ Str::limit($lem->alamat ?? 'Alamat belum diatur', 45) }}
                            </div>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:12px; font-weight:600; color:#0891b2; background:rgba(8,145,178,0.1); padding:4px 10px; border-radius:12px;">
                                {{ $lem->jenis_label }}
                            </span>
                        </td>
                        <td style="padding:12px 16px; font-size:13.5px; font-weight:500; color:#1e293b;">
                            {{ $lem->kepala }}
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569;">
                            <div>{{ $lem->telepon ?? '-' }}</div>
                        </td>
                        <td style="padding:12px 16px; text-align:center;">
                            <form method="POST" action="{{ route('super-admin.lembaga.toggle', $lem->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background:none; border:none; padding:0; cursor:pointer;">
                                    @if($lem->is_active)
                                        <span class="badge badge-aktif" title="Klik untuk menonaktifkan">Aktif</span>
                                    @else
                                        <span class="badge badge-tidak_aktif" title="Klik untuk mengaktifkan">Tidak Aktif</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td style="padding:12px 20px; text-align:center;">
                            <div style="display:flex; justify-content:center; gap:8px;">
                                <a href="{{ route('super-admin.lembaga.show', $lem->id) }}" class="btn btn-secondary btn-sm btn-icon" title="Detail">
                                    <i class="ph ph-eye" style="font-size:15px;"></i>
                                </a>
                                <a href="{{ route('super-admin.lembaga.edit', $lem->id) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit" style="color:#f59e0b;">
                                    <i class="ph ph-pencil-simple" style="font-size:15px;"></i>
                                </a>
                                <form method="POST" action="{{ route('super-admin.lembaga.destroy', $lem->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lembaga ini? Semua data relasi (jika ada) akan dicek.')" style="display:inline;">
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
    @if($lembagaList->hasPages())
        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $lembagaList->links() }}
        </div>
    @endif

</div>

@endsection
