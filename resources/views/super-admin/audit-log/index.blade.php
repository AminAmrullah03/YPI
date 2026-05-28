@extends('layouts.app')

@section('title', 'Audit Log Aktivitas')
@section('page-title', 'Audit Log')

@section('content')

<div class="content-area">

    {{-- Filter Card --}}
    <div class="card" style="margin-bottom:24px; padding:20px;">
        <form method="GET" action="{{ route('super-admin.audit-log.index') }}" style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
            
            <div style="flex:1; min-width:200px;">
                <label class="form-label">Cari Deskripsi</label>
                <div style="position:relative;">
                    <i class="ph ph-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:16px;"></i>
                    <input type="text" name="search" class="form-input" placeholder="Cari kata kunci aktivitas..." value="{{ request('search') }}" style="padding-left:38px;">
                </div>
            </div>

            <div style="width:160px;">
                <label class="form-label">Pengguna</label>
                <select name="user_id" class="form-select">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="width:130px;">
                <label class="form-label">Aksi</label>
                <select name="action" class="form-select">
                    <option value="">Semua Aksi</option>
                    @foreach(\App\Models\ActivityLog::ACTION_LABELS as $act => $label)
                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width:130px;">
                <label class="form-label">Model</label>
                <select name="model" class="form-select">
                    <option value="">Semua Data</option>
                    <option value="Siswa" {{ request('model') == 'Siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="Guru" {{ request('model') == 'Guru' ? 'selected' : '' }}>Guru</option>
                    <option value="Lembaga" {{ request('model') == 'Lembaga' ? 'selected' : '' }}>Lembaga</option>
                    <option value="User" {{ request('model') == 'User' ? 'selected' : '' }}>Pengguna</option>
                </select>
            </div>

            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn btn-primary" style="height:41px; padding:0 20px;">
                    <i class="ph ph-sliders-horizontal" style="font-size:16px;"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'user_id', 'action', 'model']))
                    <a href="{{ route('super-admin.audit-log.index') }}" class="btn btn-secondary" style="height:41px; display:inline-flex; align-items:center; justify-content:center; padding:0 16px;">Reset</a>
                @endif
            </div>

        </form>
    </div>

    {{-- Table Card --}}
    <div class="table-wrapper">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:14px 20px; width:180px;">Waktu</th>
                    <th style="padding:14px 16px; width:160px;">Pengguna</th>
                    <th style="padding:14px 16px; width:130px;">Aksi</th>
                    <th style="padding:14px 16px; width:120px;">Model</th>
                    <th style="padding:14px 16px;">Deskripsi Aktivitas</th>
                    <th style="padding:14px 16px; width:130px;">IP Address</th>
                    <th style="padding:14px 20px; text-align:center; width:80px;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @if($logs->isEmpty())
                    <tr>
                        <td colspan="7" style="padding:40px; text-align:center; color:#94a3b8;">
                            <i class="ph ph-clock-counter-clockwise" style="font-size:40px; display:block; margin-bottom:8px;"></i>
                            Belum ada log aktivitas yang tercatat.
                        </td>
                    </tr>
                @else
                    @foreach($logs as $log)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding:12px 20px; font-size:13px; color:#475569; font-weight:500;">
                            {{ $log->created_at?->format('d M Y, H:i:s') ?? '—' }}
                        </td>
                        <td style="padding:12px 16px;">
                            <div style="font-weight:700; font-size:13.5px; color:#1e293b;">{{ $log->user?->name ?? 'System' }}</div>
                            <div style="font-size:11px; color:#94a3b8; margin-top:2px;">{{ $log->user?->username ?? 'system' }}</div>
                        </td>
                        <td style="padding:12px 16px;">
                            @php
                                $actionColors = ['create'=>'#10b981','update'=>'#f59e0b','delete'=>'#ef4444'];
                                $color = $actionColors[$log->action] ?? '#94a3b8';
                            @endphp
                            <span style="font-size:11.5px; font-weight:600; color:{{ $color }}; background:{{ $color }}1a; padding:3px 10px; border-radius:12px;">
                                {{ $log->action_label }}
                            </span>
                        </td>
                        <td style="padding:12px 16px; font-size:13px; color:#475569; font-weight:600;">
                            {{ $log->model }}
                        </td>
                        <td style="padding:12px 16px; font-size:13.5px; color:#1e293b; line-height:1.4;">
                            {{ $log->description }}
                        </td>
                        <td style="padding:12px 16px; font-family:monospace; font-size:12.5px; color:#94a3b8;">
                            {{ $log->ip_address }}
                        </td>
                        <td style="padding:12px 20px; text-align:center;">
                            @if($log->old_data || $log->new_data)
                                <button type="button" class="btn btn-secondary btn-sm btn-icon" title="Lihat Data Perubahan" onclick="showChangeDetail({{ $log->id }}, '{{ addslashes($log->description) }}')" style="color:#0891b2;">
                                    <i class="ph ph-info" style="font-size:15px;"></i>
                                </button>
                                <div id="log-detail-{{ $log->id }}" style="display:none;">
                                    <div class="old-json">@json($log->old_data)</div>
                                    <div class="new-json">@json($log->new_data)</div>
                                </div>
                            @else
                                <span style="font-size:12px; color:#cbd5e1;">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $logs->links() }}
        </div>
    @endif

</div>

{{-- Detail Modal --}}
<div id="detail-modal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(4px);">
    <div class="card" style="width:100%; max-width:640px; max-height:80vh; display:flex; flex-direction:column; padding:0; overflow:hidden; box-shadow:0 24px 48px rgba(0,0,0,0.2);">
        <div style="padding:20px 24px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; background:#f8fafc;">
            <h3 id="modal-title" style="margin:0; font-size:15px; font-weight:700; color:#1e293b;">Detail Perubahan Data</h3>
            <button onclick="closeModal()" style="background:none; border:none; cursor:pointer; color:#94a3b8; font-size:20px;"><i class="ph ph-x"></i></button>
        </div>
        <div style="padding:24px; overflow-y:auto; flex:1; display:flex; flex-direction:column; gap:16px;">
            <div style="font-size:13.5px; color:#475569; font-weight:500;" id="modal-desc"></div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                    <div style="font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; margin-bottom:6px;">Data Lama</div>
                    <pre id="modal-old" style="margin:0; background:#f1f5f9; padding:12px; border-radius:8px; font-family:monospace; font-size:12px; overflow-x:auto; max-height:300px; color:#334155; border:1px solid #e2e8f0;"></pre>
                </div>
                <div>
                    <div style="font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; margin-bottom:6px;">Data Baru</div>
                    <pre id="modal-new" style="margin:0; background:#f0fdf4; padding:12px; border-radius:8px; font-family:monospace; font-size:12px; overflow-x:auto; max-height:300px; color:#166534; border:1px solid #dcfce7;"></pre>
                </div>
            </div>
        </div>
        <div style="padding:16px 24px; border-top:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:flex-end;">
            <button onclick="closeModal()" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showChangeDetail(id, desc) {
    const logDiv = document.getElementById('log-detail-' + id);
    const oldVal = JSON.parse(logDiv.querySelector('.old-json').textContent || 'null');
    const newVal = JSON.parse(logDiv.querySelector('.new-json').textContent || 'null');
    
    document.getElementById('modal-desc').textContent = desc;
    document.getElementById('modal-old').textContent = oldVal ? JSON.stringify(oldVal, null, 2) : 'Tidak ada data';
    document.getElementById('modal-new').textContent = newVal ? JSON.stringify(newVal, null, 2) : 'Tidak ada data';
    
    const modal = document.getElementById('detail-modal');
    modal.style.display = 'flex';
}

function closeModal() {
    document.getElementById('detail-modal').style.display = 'none';
}

// Close on escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endpush
