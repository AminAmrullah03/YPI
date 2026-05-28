@extends('layouts.app')

@section('title', 'Data Siswa YPI')
@section('page-title', 'Data Siswa')

@section('content')

<div class="content-area">

    {{-- Filter & Search --}}
    <div class="card" style="margin-bottom:24px; padding:20px;">
        <form method="GET" action="{{ route('super-admin.siswa.index') }}" style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
            
            <div style="flex:1; min-width:200px;">
                <label class="form-label">Cari Siswa</label>
                <div style="position:relative;">
                    <i class="ph ph-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:16px;"></i>
                    <input type="text" name="search" class="form-input" placeholder="Nama, NIS, atau NIK..." value="{{ request('search') }}" style="padding-left:38px;">
                </div>
            </div>

            <div style="width:160px;">
                <label class="form-label">Lembaga</label>
                <select name="lembaga_id" class="form-select">
                    <option value="">Semua Lembaga</option>
                    @foreach($lembagaList as $lem)
                        <option value="{{ $lem->id }}" {{ request('lembaga_id') == $lem->id ? 'selected' : '' }}>
                            {{ $lem->jenis_label }} — {{ $lem->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="width:130px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Siswa::STATUS_LIST as $val => $label)
                        <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width:130px;">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select">
                    <option value="">Semua</option>
                    <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn btn-primary" style="height:41px; padding:0 20px;">
                    <i class="ph ph-sliders-horizontal" style="font-size:16px;"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'lembaga_id', 'status', 'jenis_kelamin']))
                    <a href="{{ route('super-admin.siswa.index') }}" class="btn btn-secondary" style="height:41px; display:inline-flex; align-items:center; justify-content:center; padding:0 16px;">Reset</a>
                @endif
            </div>

        </form>
    </div>

    {{-- Table Card --}}
    <div class="table-wrapper">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:14px 20px; width:50px;">Foto</th>
                    <th style="padding:14px 16px;">Nama Lengkap</th>
                    <th style="padding:14px 16px;">NIS / NIK</th>
                    <th style="padding:14px 16px;">Lembaga</th>
                    <th style="padding:14px 16px;">Kelas</th>
                    <th style="padding:14px 16px;">JK</th>
                    <th style="padding:14px 16px; text-align:center;">Status</th>
                    <th style="padding:14px 20px; text-align:center; width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($siswaList->isEmpty())
                    <tr>
                        <td colspan="8" style="padding:40px; text-align:center; color:#94a3b8;">
                            <i class="ph ph-student" style="font-size:40px; display:block; margin-bottom:8px;"></i>
                            Data siswa tidak ditemukan.
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
                        <td style="padding:12px 16px;">
                            <span style="font-size:11px; font-weight:700; color:#0891b2; background:rgba(8,145,178,0.1); padding:2px 8px; border-radius:10px;">
                                {{ $sis->lembaga?->jenis_label }}
                            </span>
                            <div style="font-size:12px; color:#475569; margin-top:4px; font-weight:500;">{{ $sis->lembaga?->nama }}</div>
                        </td>
                        <td style="padding:12px 16px; font-size:13.5px; font-weight:600; color:#1e293b;">
                            {{ $sis->kelas }}
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569; font-weight:500;">
                            {{ $sis->jenis_kelamin }}
                        </td>
                        <td style="padding:12px 16px; text-align:center;">
                            <span class="badge badge-{{ $sis->status }}">{{ $sis->status_label }}</span>
                        </td>
                        <td style="padding:12px 20px; text-align:center;">
                            <a href="{{ route('super-admin.siswa.show', $sis->id) }}" class="btn btn-secondary btn-sm btn-icon" title="Lihat Profil">
                                <i class="ph ph-eye" style="font-size:15px;"></i>
                            </a>
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
