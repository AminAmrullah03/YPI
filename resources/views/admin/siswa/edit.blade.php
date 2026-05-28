@extends('layouts.app')

@section('title', 'Ubah Data Siswa')
@section('page-title', 'Data Siswa')
@section('page-subtitle', 'Ubah Siswa')

@section('content')

<div class="content-area">

    <div style="max-width: 800px; margin: 0 auto;">
        
        {{-- Back Button --}}
        <div style="margin-bottom:16px;">
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px;">
                <i class="ph ph-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card">
            <div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:12px; display:flex; align-items:center; gap:8px;">
                <i class="ph ph-pencil-simple" style="color:#10b981;"></i>
                Form Ubah Data Siswa: {{ $siswa->nama }}
            </div>

            <form method="POST" action="{{ route('admin.siswa.update', $siswa->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    
                    {{-- Nama Lengkap --}}
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="nama" class="form-label">Nama Lengkap Siswa <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-input @error('nama') is-invalid @enderror" value="{{ old('nama', $siswa->nama) }}" required>
                        @error('nama')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- NIS --}}
                    <div class="form-group">
                        <label for="nis" class="form-label">Nomor Induk Siswa (NIS)</label>
                        <input type="text" name="nis" id="nis" class="form-input @error('nis') is-invalid @enderror" value="{{ old('nis', $siswa->nis) }}">
                        @error('nis')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div class="form-group">
                        <label for="nik" class="form-label">NIK Siswa (No. KTP/KIA)</label>
                        <input type="text" name="nik" id="nik" class="form-input @error('nik') is-invalid @enderror" value="{{ old('nik', $siswa->nik) }}" maxlength="16">
                        @error('nik')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="form-group">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span style="color:#ef4444;">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kelas --}}
                    <div class="form-group">
                        <label for="kelas" class="form-label">Kelas / Rombel</label>
                        <input type="text" name="kelas" id="kelas" class="form-input @error('kelas') is-invalid @enderror" value="{{ old('kelas', $siswa->kelas) }}">
                        @error('kelas')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="form-group">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-input @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                        @error('tempat_lahir')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="form-group">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-input @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d')) }}">
                        @error('tanggal_lahir')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tanggal Masuk --}}
                    <div class="form-group">
                        <label for="tanggal_masuk" class="form-label">Tanggal Mulai Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-input @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', $siswa->tanggal_masuk?->format('Y-m-d')) }}">
                        @error('tanggal_masuk')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Status Siswa --}}
                    <div class="form-group">
                        <label for="status" class="form-label">Status Siswa <span style="color:#ef4444;">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach(\App\Models\Siswa::STATUS_LIST as $val => $label)
                                <option value="{{ $val }}" {{ old('status', $siswa->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Program Siswa --}}
                    <div class="form-group">
                        <label for="program" class="form-label">Program Siswa <span style="color:#ef4444;">*</span></label>
                        <select name="program" id="program" class="form-select @error('program') is-invalid @enderror" required>
                            @foreach(\App\Models\Siswa::PROGRAM_LIST as $val => $label)
                                <option value="{{ $val }}" {{ old('program', $siswa->program) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('program')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="foto" class="form-label">Foto Siswa</label>
                        <div style="display:flex; align-items:center; gap:16px;">
                            @if($siswa->foto)
                                <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto Siswa" style="width:64px; height:64px; border-radius:12px; object-fit:cover; border:1px solid #e2e8f0;">
                            @else
                                <div style="width:64px; height:64px; background:#f1f5f9; border-radius:12px; display:flex; align-items:center; justify-content:center; border:1px dashed #cbd5e1;">
                                    <i class="ph ph-user" style="color:#94a3b8; font-size:24px;"></i>
                                </div>
                            @endif
                            <div style="flex:1;">
                                <input type="file" name="foto" id="foto" class="form-input @error('foto') is-invalid @enderror" accept="image/*">
                                <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;">Format: JPG, PNG. Kosongkan jika tidak ingin mengganti.</small>
                            </div>
                        </div>
                        @error('foto')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="alamat" class="form-label">Alamat Tinggal</label>
                        <textarea name="alamat" id="alamat" class="form-textarea @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $siswa->alamat) }}</textarea>
                        @error('alamat')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Heading Wali --}}
                    <div style="grid-column: span 2; font-size:14px; font-weight:700; color:#1e293b; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-top:8px;">
                        Data Orang Tua / Wali
                    </div>

                    {{-- Nama Wali --}}
                    <div class="form-group">
                        <label for="nama_wali" class="form-label">Nama Orang Tua / Wali</label>
                        <input type="text" name="nama_wali" id="nama_wali" class="form-input @error('nama_wali') is-invalid @enderror" value="{{ old('nama_wali', $siswa->nama_wali) }}">
                        @error('nama_wali')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Telepon Wali --}}
                    <div class="form-group">
                        <label for="telepon_wali" class="form-label">No. Telepon / HP Wali</label>
                        <input type="text" name="telepon_wali" id="telepon_wali" class="form-input @error('telepon_wali') is-invalid @enderror" value="{{ old('telepon_wali', $siswa->telepon_wali) }}">
                        @error('telepon_wali')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div style="border-top:1px solid #e2e8f0; padding-top:16px; margin-top:12px; display:flex; justify-content:flex-end; gap:12px;">
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>
        </div>

    </div>

</div>

@endsection
