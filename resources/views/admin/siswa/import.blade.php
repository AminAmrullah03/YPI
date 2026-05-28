@extends('layouts.app')

@section('title', 'Import Data Siswa')
@section('page-title', 'Data Siswa')
@section('page-subtitle', 'Import Siswa')

@section('content')

<div class="content-area">

    <div style="max-width: 680px; margin: 0 auto;">
        
        {{-- Back Button --}}
        <div style="margin-bottom:16px;">
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px;">
                <i class="ph ph-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card" style="margin-bottom:20px;">
            <div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:12px; display:flex; align-items:center; gap:8px;">
                <i class="ph ph-file-xls" style="color:#10b981; font-size:20px;"></i>
                Import Massal Siswa via Excel
            </div>

            <div style="background:rgba(16,185,129,0.06); border-left:4px solid #10b981; border-radius:8px; padding:16px; margin-bottom:24px; font-size:13.5px; color:#1e293b; line-height:1.6;">
                <div style="font-weight:700; margin-bottom:6px; color:#065f46;">Langkah Import Data:</div>
                <ol style="margin:0; padding-left:20px; display:flex; flex-direction:column; gap:4px;">
                    <li>Unduh template Excel dengan tombol di bawah.</li>
                    <li>Isi data siswa Anda ke dalam file Excel tersebut sesuai dengan kolom & format contoh.</li>
                    <li>Pilih file Excel yang telah Anda isi, lalu klik tombol <strong>Unggah & Preview Data</strong>.</li>
                    <li>Sistem akan melakukan pemeriksaan validasi data sebelum menyimpannya ke database.</li>
                </ol>
            </div>

            {{-- Download Template Button --}}
            <div style="margin-bottom:24px; text-align:center; padding:16px; border:1px dashed #cbd5e1; border-radius:12px; background:#f8fafc;">
                <div style="font-size:13px; color:#64748b; margin-bottom:8px; font-weight:500;">Unduh Template Excel Resmi:</div>
                <a href="{{ route('admin.siswa.template') }}" class="btn btn-secondary btn-sm" style="border-color:#10b981; color:#10b981; padding:8px 16px;">
                    <i class="ph ph-download-simple"></i> Download Template Siswa
                </a>
            </div>

            {{-- Upload Form --}}
            <form method="POST" action="{{ route('admin.siswa.import') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group" style="margin-bottom:24px;">
                    <label for="file" class="form-label">Pilih File Excel (.xlsx, .xls, .csv)</label>
                    <input type="file" name="file" id="file" class="form-input @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required style="padding:8px 12px;">
                    @error('file')
                        <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="border-top:1px solid #e2e8f0; padding-top:16px; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary" style="padding:10px 20px;">
                        Unggah & Preview Data <i class="ph ph-caret-right" style="margin-left:4px;"></i>
                    </button>
                </div>

            </form>
        </div>

    </div>

</div>

@endsection
