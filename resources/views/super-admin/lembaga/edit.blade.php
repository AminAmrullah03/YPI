@extends('layouts.app')

@section('title', 'Ubah Lembaga')
@section('page-title', 'Manajemen Lembaga')
@section('page-subtitle', 'Ubah Lembaga')

@section('content')

<div class="content-area">

    <div style="max-width: 680px; margin: 0 auto;">
        
        {{-- Back Button --}}
        <div style="margin-bottom:16px;">
            <a href="{{ route('super-admin.lembaga.index') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px;">
                <i class="ph ph-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card">
            <div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:12px; display:flex; align-items:center; gap:8px;">
                <i class="ph ph-pencil-simple" style="color:#10b981;"></i>
                Form Ubah Lembaga: {{ $lembaga->nama }}
            </div>

            <form method="POST" action="{{ route('super-admin.lembaga.update', $lembaga->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    
                    {{-- Nama Lembaga --}}
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="nama" class="form-label">Nama Lembaga <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-input @error('nama') is-invalid @enderror" value="{{ old('nama', $lembaga->nama) }}" required>
                        @error('nama')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Jenis Lembaga --}}
                    <div class="form-group">
                        <label for="jenis" class="form-label">Jenis Lembaga <span style="color:#ef4444;">*</span></label>
                        <select name="jenis" id="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach(\App\Models\Lembaga::JENIS as $jenis)
                                <option value="{{ $jenis }}" {{ old('jenis', $lembaga->jenis) == $jenis ? 'selected' : '' }}>
                                    {{ \App\Models\Lembaga::JENIS_LABEL[$jenis] }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kepala Lembaga --}}
                    <div class="form-group">
                        <label for="kepala" class="form-label">Kepala Lembaga <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="kepala" id="kepala" class="form-input @error('kepala') is-invalid @enderror" value="{{ old('kepala', $lembaga->kepala) }}" required>
                        @error('kepala')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div class="form-group">
                        <label for="telepon" class="form-label">No. Telepon / WA</label>
                        <input type="text" name="telepon" id="telepon" class="form-input @error('telepon') is-invalid @enderror" value="{{ old('telepon', $lembaga->telepon) }}">
                        @error('telepon')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Logo Lembaga --}}
                    <div class="form-group">
                        <label for="logo" class="form-label">Logo Lembaga</label>
                        <div style="display:flex; align-items:center; gap:12px;">
                            @if($lembaga->logo)
                                <img src="{{ asset('storage/' . $lembaga->logo) }}" alt="Logo" style="width:48px; height:48px; border-radius:8px; object-fit:cover; border:1px solid #e2e8f0; flex-shrink:0;">
                            @endif
                            <div style="flex:1;">
                                <input type="file" name="logo" id="logo" class="form-input @error('logo') is-invalid @enderror" accept="image/*">
                                <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;">Format: JPG, PNG. Kosongkan jika tidak ingin mengganti.</small>
                            </div>
                        </div>
                        @error('logo')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" class="form-textarea @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $lembaga->alamat) }}</textarea>
                        @error('alamat')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Status Aktif --}}
                    <div class="form-group" style="grid-column: span 2; display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $lembaga->is_active) ? 'checked' : '' }} style="width:16px; height:16px; accent-color:#10b981; cursor:pointer;">
                        <label for="is_active" style="font-size:13.5px; font-weight:600; color:#1e293b; cursor:pointer; user-select:none;">Lembaga ini aktif</label>
                    </div>

                </div>

                <div style="border-top:1px solid #e2e8f0; padding-top:16px; display:flex; justify-content:flex-end; gap:12px;">
                    <a href="{{ route('super-admin.lembaga.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>
        </div>

    </div>

</div>

@endsection
