@extends('layouts.app')

@section('title', 'Ganti Password Mandiri')
@section('page-title', 'Ganti Password')

@section('content')

<div class="content-area">

    <div style="max-width: 500px; margin: 0 auto;">
        
        <div class="card">
            <div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:12px; display:flex; align-items:center; gap:8px;">
                <i class="ph ph-key" style="color:#10b981; font-size:20px;"></i>
                Ganti Password Akun Anda
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div style="display:flex; flex-direction:column; gap:16px;">
                    
                    {{-- Current Password --}}
                    <div class="form-group">
                        <label for="current_password" class="form-label">Password Saat Ini <span style="color:#ef4444;">*</span></label>
                        <input type="password" name="current_password" id="current_password" class="form-input @error('current_password') is-invalid @enderror" placeholder="Masukkan password Anda saat ini..." required>
                        @error('current_password')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru <span style="color:#ef4444;">*</span></label>
                        <input type="password" name="password" id="password" class="form-input @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter..." required>
                        @error('password')
                            <span style="font-size:12px; color:#ef4444; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group" style="margin-bottom:20px;">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span style="color:#ef4444;">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Ketik ulang password baru..." required>
                    </div>

                </div>

                <div style="border-top:1px solid #e2e8f0; padding-top:16px; display:flex; justify-content:flex-end; gap:12px;">
                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('super-admin.dashboard') }}" class="btn btn-secondary">Batal</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
                    @endif
                    <button type="submit" class="btn btn-primary">Ganti Password</button>
                </div>

            </form>
        </div>

    </div>

</div>

@endsection
