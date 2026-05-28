@extends('layouts.app')

@section('title', 'Ubah Data Guru')
@section('page-title', 'Data Guru')
@section('page-subtitle', 'Ubah Guru')

@section('content')

<div class="content-area">

    <div style="max-width: 800px; margin: 0 auto;">
        
        {{-- Back Button --}}
        <div style="margin-bottom:16px;">
            <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px;">
                <i class="ph ph-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card">
            <div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:12px; display:flex; align-items:center; gap:8px;">
                <i class="ph ph-pencil-simple" style="color:#10b981;"></i>
                Form Ubah Data Guru: {{ $guru->nama }}
            </div>

            <form method="POST" action="{{ route('admin.guru.update', $guru->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    
                    {{-- Nama Lengkap --}}
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="nama" class="form-label">Nama Lengkap Guru <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-input @error('nama') is-invalid @enderror" value="{{ old('nama', $guru->nama) }}" required>
                        @error('nama')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div class="form-group">
                        <label for="nik" class="form-label">NIK Guru <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="nik" id="nik" class="form-input @error('nik') is-invalid @enderror" value="{{ old('nik', $guru->nik) }}" maxlength="16" required>
                        @error('nik')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- NUPTK --}}
                    <div class="form-group">
                        <label for="nuptk" class="form-label">NUPTK (Jika Ada)</label>
                        <input type="text" name="nuptk" id="nuptk" class="form-input @error('nuptk') is-invalid @enderror" value="{{ old('nuptk', $guru->nuptk) }}" maxlength="16">
                        @error('nuptk')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="form-group">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span style="color:#ef4444;">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Pendidikan Terakhir --}}
                    <div class="form-group">
                        <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-input @error('pendidikan_terakhir') is-invalid @enderror" value="{{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) }}">
                        @error('pendidikan_terakhir')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="form-group">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-input @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}">
                        @error('tempat_lahir')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="form-group">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-input @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', $guru->tanggal_lahir?->format('Y-m-d')) }}">
                        @error('tanggal_lahir')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- No. Telepon --}}
                    <div class="form-group">
                        <label for="telepon" class="form-label">No. Telepon / HP</label>
                        <input type="text" name="telepon" id="telepon" class="form-input @error('telepon') is-invalid @enderror" value="{{ old('telepon', $guru->telepon) }}">
                        @error('telepon')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div class="form-group">
                        <label for="foto" class="form-label">Foto Guru</label>
                        <div style="display:flex; align-items:center; gap:16px;">
                            @if($guru->foto)
                                <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto Guru" style="width:64px; height:64px; border-radius:12px; object-fit:cover; border:1px solid #e2e8f0;">
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
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" class="form-textarea @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $guru->alamat) }}</textarea>
                        @error('alamat')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Heading Kepegawaian --}}
                    <div style="grid-column: span 2; font-size:14px; font-weight:700; color:#1e293b; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-top:8px;">
                        Data Jabatan & Penugasan
                    </div>

                    {{-- Jabatan --}}
                    <div class="form-group">
                        <label for="jabatan" class="form-label">Jabatan Utama <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="jabatan" id="jabatan" class="form-input @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $guru->jabatan) }}" required>
                        @error('jabatan')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Mata Pelajaran --}}
                    <div class="form-group">
                        <label for="mata_pelajaran" class="form-label">Mata Pelajaran (Jika Ada)</label>
                        <input type="text" name="mata_pelajaran" id="mata_pelajaran" class="form-input @error('mata_pelajaran') is-invalid @enderror" value="{{ old('mata_pelajaran', $guru->mata_pelajaran) }}">
                        @error('mata_pelajaran')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tanggal Masuk --}}
                    <div class="form-group">
                        <label for="tanggal_masuk" class="form-label">Tanggal Mulai Tugas <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-input @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', $guru->tanggal_masuk?->format('Y-m-d')) }}" required>
                        @error('tanggal_masuk')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tanggal Keluar --}}
                    <div class="form-group">
                        <label for="tanggal_keluar" class="form-label">Tanggal Berhenti Tugas (Kosongkan jika aktif)</label>
                        <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-input @error('tanggal_keluar') is-invalid @enderror" value="{{ old('tanggal_keluar', $guru->tanggal_keluar?->format('Y-m-d')) }}">
                        @error('tanggal_keluar')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Status Kepegawaian --}}
                    <div class="form-group">
                        <label for="status_kepegawaian" class="form-label">Status Kepegawaian <span style="color:#ef4444;">*</span></label>
                        <select name="status_kepegawaian" id="status_kepegawaian" class="form-select @error('status_kepegawaian') is-invalid @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            @foreach(\App\Models\Guru::STATUS_KEPEGAWAIAN_LIST as $val => $label)
                                <option value="{{ $val }}" {{ old('status_kepegawaian', $guru->status_kepegawaian) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status_kepegawaian')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="form-group">
                        <label for="status" class="form-label">Status Keaktifan <span style="color:#ef4444;">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach(\App\Models\Guru::STATUS_LIST as $val => $label)
                                <option value="{{ $val }}" {{ old('status', $guru->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div style="border-top:1px solid #e2e8f0; padding-top:16px; margin-top:12px; display:flex; justify-content:flex-end; gap:12px;">
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>
        </div>

    </div>

</div>

@endsection
