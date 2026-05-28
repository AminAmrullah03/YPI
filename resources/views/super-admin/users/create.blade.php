@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')
@section('page-title', 'Manajemen Pengguna')
@section('page-subtitle', 'Tambah Pengguna')

@section('content')

<div class="content-area">

    <div style="max-width: 600px; margin: 0 auto;">
        
        {{-- Back Button --}}
        <div style="margin-bottom:16px;">
            <a href="{{ route('super-admin.users.index') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px;">
                <i class="ph ph-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card">
            <div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:12px; display:flex; align-items:center; gap:8px;">
                <i class="ph ph-plus-circle" style="color:#10b981;"></i>
                Form Tambah Pengguna Baru
            </div>

            <form method="POST" action="{{ route('super-admin.users.store') }}">
                @csrf

                <div style="display:flex; flex-direction:column; gap:16px;">
                    
                    {{-- Nama Lengkap --}}
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" id="name" class="form-input @error('name') is-invalid @enderror" placeholder="Nama lengkap pengguna..." value="{{ old('name') }}" required>
                        @error('name')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="form-group">
                        <label for="username" class="form-label">Username <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="username" id="username" class="form-input @error('username') is-invalid @enderror" placeholder="Contoh: admin.sd atau admin.smp" value="{{ old('username') }}" required>
                        <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;">Digunakan untuk login. Disarankan menggunakan format `admin.{lembaga}`.</small>
                        @error('username')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label for="password" class="form-label">Password <span style="color:#ef4444;">*</span></label>
                        <input type="password" name="password" id="password" class="form-input @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter..." required>
                        @error('password')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="form-group">
                        <label for="role" class="form-label">Role Akses <span style="color:#ef4444;">*</span></label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required onchange="toggleLembagaSelect()">
                            <option value="admin_lembaga" {{ old('role', 'admin_lembaga') == 'admin_lembaga' ? 'selected' : '' }}>Admin Lembaga (Mengelola lembaga tertentu)</option>
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin (Mengelola seluruh yayasan)</option>
                        </select>
                        @error('role')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Lembaga Select --}}
                    <div class="form-group" id="lembaga-group">
                        <label for="lembaga_id" class="form-label">Lembaga Naungan <span style="color:#ef4444;">*</span></label>
                        <select name="lembaga_id" id="lembaga_id" class="form-select @error('lembaga_id') is-invalid @enderror">
                            <option value="">-- Pilih Lembaga --</option>
                            @foreach($lembagaList as $lem)
                                <option value="{{ $lem->id }}" {{ old('lembaga_id') == $lem->id ? 'selected' : '' }}>
                                    {{ $lem->nama }} ({{ $lem->jenis_label }})
                                </option>
                            @endforeach
                        </select>
                        @error('lembaga_id')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div style="border-top:1px solid #e2e8f0; padding-top:16px; margin-top:12px; display:flex; justify-content:flex-end; gap:12px;">
                    <a href="{{ route('super-admin.users.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
                </div>

            </form>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
function toggleLembagaSelect() {
    const roleSelect = document.getElementById('role');
    const lembagaGroup = document.getElementById('lembaga-group');
    const lembagaSelect = document.getElementById('lembaga_id');
    
    if (roleSelect.value === 'super_admin') {
        lembagaGroup.style.display = 'none';
        lembagaSelect.removeAttribute('required');
        lembagaSelect.value = '';
    } else {
        lembagaGroup.style.display = 'block';
        lembagaSelect.setAttribute('required', 'required');
    }
}

// Run on load to set correct state
document.addEventListener('DOMContentLoaded', function() {
    toggleLembagaSelect();
});
</script>
@endpush
