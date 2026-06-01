@extends('layouts.app')

@section('title', 'Laporan & Export Lembaga')
@section('page-title', 'Laporan')

@section('content')

<div class="content-area">

    {{-- Stat Cards --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; margin-bottom:28px;">
        
        <div class="stat-card">
            <div class="stat-card__icon" style="background:rgba(16,185,129,0.12);">
                <i class="ph ph-student" style="color:#10b981;"></i>
            </div>
            <div>
                <div class="stat-card__value">{{ number_format($rekap['siswa_aktif']) }}</div>
                <div class="stat-card__label">Siswa Aktif</div>
                <div style="font-size:11px; color:#94a3b8; margin-top:2px;">dari {{ $rekap['total_siswa'] }} terdaftar</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon" style="background:rgba(8,145,178,0.12);">
                <i class="ph ph-chalkboard-teacher" style="color:#0891b2;"></i>
            </div>
            <div>
                <div class="stat-card__value">{{ number_format($rekap['guru_aktif']) }}</div>
                <div class="stat-card__label">Guru Aktif</div>
                <div style="font-size:11px; color:#94a3b8; margin-top:2px;">dari {{ $rekap['total_guru'] }} terdaftar</div>
            </div>
        </div>

        <div class="stat-card" style="grid-column: span 2; display:flex; justify-content:space-between; align-items:center; background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border:none;">
            <div style="display:flex; align-items:center; gap:16px;">
                <div class="stat-card__icon" style="background:rgba(255,255,255,0.1);">
                    <i class="ph ph-file-pdf" style="color:#ef4444; font-size:24px;"></i>
                </div>
                <div>
                    <div style="font-size:16px; font-weight:700; color:#fff;">Laporan PDF Lengkap</div>
                    <div style="font-size:12px; color:rgba(255,255,255,0.6); margin-top:4px;">Unduh seluruh data siswa & guru {{ $lembaga->nama }} format PDF.</div>
                </div>
            </div>
            <a href="{{ route('admin.laporan.export-pdf') }}" class="btn btn-primary" style="background:#ef4444; border-radius:12px; box-shadow: 0 4px 12px rgba(239,68,68,0.3);">
                <i class="ph ph-download-simple"></i> Download PDF
            </a>
        </div>

    </div>

    {{-- Export Sections --}}
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
        
        {{-- Export Siswa --}}
        <div class="card">
            <div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:16px; border-bottom:1px solid #f1f5f9; padding-bottom:10px; display:flex; align-items:center; gap:8px;">
                <i class="ph ph-student" style="color:#10b981; font-size:20px;"></i>
                Export Data Siswa (Excel)
            </div>
            <p style="font-size:13.5px; color:#64748b; margin:0 0 20px; line-height:1.5;">
                Unduh seluruh data siswa lembaga Anda. Anda dapat memfilter berdasarkan status keaktifan.
            </p>

            <form method="GET" action="{{ route('admin.laporan.export-siswa') }}">
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="form-label">Status Siswa</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\Siswa::STATUS_LIST as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:12px;">
                    <label class="form-label">Program</label>
                    <select name="program" class="form-select">
                        <option value="">Semua Program</option>
                        @foreach(\App\Models\Siswa::PROGRAM_LIST as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:24px;">
                    <label class="form-label">Status SKTM</label>
                    <select name="status_sktm" class="form-select">
                        <option value="">Semua SKTM</option>
                        @foreach(\App\Models\Siswa::STATUS_SKTM_LIST as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:12px; border-radius:10px;">
                    <i class="ph ph-file-xls" style="font-size:18px;"></i>
                    Download Siswa Excel
                </button>
            </form>
        </div>

        {{-- Export Guru --}}
        <div class="card">
            <div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:16px; border-bottom:1px solid #f1f5f9; padding-bottom:10px; display:flex; align-items:center; gap:8px;">
                <i class="ph ph-chalkboard-teacher" style="color:#0891b2; font-size:20px;"></i>
                Export Data Guru (Excel)
            </div>
            <p style="font-size:13.5px; color:#64748b; margin:0 0 20px; line-height:1.5;">
                Unduh seluruh data guru lembaga Anda. Anda dapat memfilter berdasarkan status keaktifan.
            </p>

            <form method="GET" action="{{ route('admin.laporan.export-guru') }}">
                <div class="form-group" style="margin-bottom:24px;">
                    <label class="form-label">Status Guru</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\Guru::STATUS_LIST as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:12px; border-radius:10px; background:#0891b2;">
                    <i class="ph ph-file-xls" style="font-size:18px;"></i>
                    Download Guru Excel
                </button>
            </form>
        </div>

    </div>

</div>

@endsection
