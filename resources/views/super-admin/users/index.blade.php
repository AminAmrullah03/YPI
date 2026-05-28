@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

<div class="content-area">

    {{-- Header Actions & Filter --}}
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:24px;">
        <div style="flex:1; min-width:300px; max-width:600px;">
            <form method="GET" action="{{ route('super-admin.users.index') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="position:relative; flex:1; min-width:200px;">
                    <i class="ph ph-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:16px;"></i>
                    <input type="text" name="search" class="form-input" placeholder="Cari nama atau username..." value="{{ request('search') }}" style="padding-left:38px;">
                </div>
                <select name="role" class="form-select" style="width:140px;">
                    <option value="">Semua Role</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin_lembaga" {{ request('role') == 'admin_lembaga' ? 'selected' : '' }}>Admin Lembaga</option>
                </select>
                <select name="lembaga_id" class="form-select" style="width:160px;">
                    <option value="">Semua Lembaga</option>
                    @foreach($lembagaList as $lem)
                        <option value="{{ $lem->id }}" {{ request('lembaga_id') == $lem->id ? 'selected' : '' }}>
                            {{ $lem->jenis_label }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary" style="padding:10px 16px;">Filter</button>
                @if(request()->anyFilled(['search', 'role', 'lembaga_id']))
                    <a href="{{ route('super-admin.users.index') }}" class="btn btn-secondary" style="padding:10px 16px;">Reset</a>
                @endif
            </form>
        </div>
        <div>
            <a href="{{ route('super-admin.users.create') }}" class="btn btn-primary">
                <i class="ph ph-plus-circle" style="font-size:16px;"></i>
                Tambah Pengguna
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="table-wrapper">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:14px 20px; width:50px;">#</th>
                    <th style="padding:14px 16px;">Nama Pengguna</th>
                    <th style="padding:14px 16px;">Username</th>
                    <th style="padding:14px 16px;">Role</th>
                    <th style="padding:14px 16px;">Lembaga</th>
                    <th style="padding:14px 16px;">Tanggal Dibuat</th>
                    <th style="padding:14px 20px; text-align:center; width:220px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($users->isEmpty())
                    <tr>
                        <td colspan="7" style="padding:40px; text-align:center; color:#94a3b8;">
                            <i class="ph ph-users" style="font-size:40px; display:block; margin-bottom:8px;"></i>
                            Data pengguna tidak ditemukan.
                        </td>
                    </tr>
                @else
                    @foreach($users as $index => $u)
                    <tr>
                        <td style="padding:12px 20px; color:#94a3b8; font-weight:600;">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td style="padding:12px 16px;">
                            <div style="font-weight:700; font-size:14px; color:#1e293b;">
                                {{ $u->name }}
                                @if($u->id === auth()->id())
                                    <span style="font-size:10px; font-weight:600; color:#10b981; background:rgba(16,185,129,0.1); padding:2px 6px; border-radius:10px; margin-left:4px;">Anda</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:12px 16px; font-family:monospace; font-size:13.5px; color:#475569; font-weight:600;">
                            {{ $u->username }}
                        </td>
                        <td style="padding:12px 16px;">
                            @if($u->isSuperAdmin())
                                <span style="font-size:12px; font-weight:600; color:#8b5cf6; background:rgba(139,92,246,0.1); padding:4px 10px; border-radius:12px;">
                                    Super Admin
                                </span>
                            @else
                                <span style="font-size:12px; font-weight:600; color:#0891b2; background:rgba(8,145,178,0.1); padding:4px 10px; border-radius:12px;">
                                    Admin Lembaga
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px 16px; font-size:13.5px; font-weight:600; color:#1e293b;">
                            {{ $u->lembaga?->nama ?? '—' }}
                            @if($u->lembaga)
                                <span style="font-size:11px; color:#94a3b8; font-weight:500; display:block;">{{ $u->lembaga->jenis_label }}</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#94a3b8;">
                            {{ $u->created_at?->format('d M Y') ?? '-' }}
                        </td>
                        <td style="padding:12px 20px; text-align:center;">
                            <div style="display:flex; justify-content:center; gap:8px;">
                                <form method="POST" action="{{ route('super-admin.users.reset-password', $u->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin mereset password pengguna ini? Password baru akan diset menjadi \'digidas2025\'.')" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color:#0891b2; font-size:12px; padding:5px 10px;" title="Reset Password">
                                        <i class="ph ph-key" style="font-size:14px;"></i> Reset
                                    </button>
                                </form>
                                <a href="{{ route('super-admin.users.edit', $u->id) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit" style="color:#f59e0b;">
                                    <i class="ph ph-pencil-simple" style="font-size:15px;"></i>
                                </a>
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('super-admin.users.destroy', $u->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary btn-sm btn-icon" title="Hapus" style="color:#ef4444; background:none; border:1px solid #e2e8f0; cursor:pointer;">
                                            <i class="ph ph-trash" style="font-size:15px;"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm btn-icon" disabled style="opacity:0.4; cursor:not-allowed;">
                                        <i class="ph ph-trash" style="font-size:15px;"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $users->links() }}
        </div>
    @endif

</div>

@endsection
