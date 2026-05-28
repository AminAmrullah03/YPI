@extends('layouts.app')

@section('title', 'Data Guru YPI')
@section('page-title', 'Data Guru')

@section('content')

<div class="content-area">

    {{-- Filter & Search --}}
    <div class="card" style="margin-bottom:24px; padding:20px;">
        <form method="GET" action="{{ route('super-admin.guru.index') }}" style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
            
            <div style="flex:1; min-width:200px;">
                <label class="form-label">Cari Guru</label>
                <div style="position:relative;">
                    <i class="ph ph-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:16px;"></i>
                    <input type="text" name="search" class="form-input" placeholder="Nama, NIK, atau NUPTK..." value="{{ request('search') }}" style="padding-left:38px;">
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
                    @foreach(\App\Models\Guru::STATUS_LIST as $val => $label)
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
                    <a href="{{ route('super-admin.guru.index') }}" class="btn btn-secondary" style="height:41px; display:inline-flex; align-items:center; justify-content:center; padding:0 16px;">Reset</a>
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
                    <th style="padding:14px 16px;">NIK / NUPTK</th>
                    <th style="padding:14px 16px;">Lembaga</th>
                    <th style="padding:14px 16px;">Jabatan</th>
                    <th style="padding:14px 16px;">Status Kerja</th>
                    <th style="padding:14px 16px; text-align:center;">Status</th>
                    <th style="padding:14px 20px; text-align:center; width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($guruList->isEmpty())
                    <tr>
                        <td colspan="8" style="padding:40px; text-align:center; color:#94a3b8;">
                            <i class="ph ph-chalkboard-teacher" style="font-size:40px; display:block; margin-bottom:8px;"></i>
                            Data guru tidak ditemukan.
                        </td>
                    </tr>
                @else
                    @foreach($guruList as $gur)
                    <tr>
                        <td style="padding:12px 20px; text-align:center;">
                            @if($gur->foto)
                                <img src="{{ asset('storage/' . $gur->foto) }}" alt="Foto" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:1px solid #e2e8f0;">
                            @else
                                <div style="width:36px; height:36px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center; border:1px solid #e2e8f0;">
                                    <i class="ph ph-user" style="color:#94a3b8; font-size:16px;"></i>
                                </div>
                            @endif
                        </td>
                        <td style="padding:12px 16px;">
                            <div style="font-weight:700; font-size:14px; color:#1e293b;">{{ $gur->nama }}</div>
                            <div style="font-size:11px; color:#94a3b8; margin-top:2px;">Masa Aktif: {{ $gur->masa_aktif }}</div>
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569;">
                            <div>NIK: <strong>{{ $gur->nik ?? '—' }}</strong></div>
                            <div style="font-size:11px; color:#94a3b8; margin-top:2px;">NUPTK: {{ $gur->nuptk ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:11px; font-weight:700; color:#0891b2; background:rgba(8,145,178,0.1); padding:2px 8px; border-radius:10px;">
                                {{ $gur->lembaga?->jenis_label }}
                            </span>
                            <div style="font-size:12px; color:#475569; margin-top:4px; font-weight:500;">{{ $gur->lembaga?->nama }}</div>
                        </td>
                        <td style="padding:12px 16px; font-size:13.5px; font-weight:600; color:#1e293b;">
                            {{ $gur->jabatan }}
                            @if($gur->mata_pelajaran)
                                <div style="font-size:11px; color:#94a3b8; font-weight:500; margin-top:2px;">Mapel: {{ $gur->mata_pelajaran }}</div>
                            @endif
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569; font-weight:600;">
                            {{ $gur->status_kepegawaian_label }}
                        </td>
                        <td style="padding:12px 16px; text-align:center;">
                            <span class="badge badge-{{ $gur->status }}">{{ $gur->status_label }}</span>
                        </td>
                        <td style="padding:12px 20px; text-align:center;">
                            <a href="{{ route('super-admin.guru.show', $gur->id) }}" class="btn btn-secondary btn-sm btn-icon" title="Lihat Profil">
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
    @if($guruList->hasPages())
        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $guruList->links() }}
        </div>
    @endif

</div>

@endsection
