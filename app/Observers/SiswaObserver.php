<?php

namespace App\Observers;

use App\Models\Siswa;
use App\Models\ActivityLog;

class SiswaObserver
{
    public function created(Siswa $siswa): void
    {
        if (!auth()->check()) {
            return;
        }

        ActivityLog::record(
            ActivityLog::ACTION_CREATE,
            'Siswa',
            $siswa->id,
            "Menambahkan siswa baru: {$siswa->nama} (" . ($siswa->lembaga?->jenis_label ?? '-') . ")",
            null,
            $siswa->getAttributes()
        );
    }

    public function updated(Siswa $siswa): void
    {
        if (!auth()->check()) {
            return;
        }

        $changes = $siswa->getChanges();
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        $oldData = array_intersect_key($siswa->getOriginal(), $changes);
        $newData = $changes;

        $desc = "Mengubah data siswa: {$siswa->nama}";
        if (isset($changes['status'])) {
            $oldStatus = Siswa::STATUS_LIST[$siswa->getOriginal('status')] ?? $siswa->getOriginal('status');
            $newStatus = Siswa::STATUS_LIST[$siswa->status] ?? $siswa->status;
            $desc = "Mengubah status siswa {$siswa->nama} dari '{$oldStatus}' menjadi '{$newStatus}'";
        }

        ActivityLog::record(
            ActivityLog::ACTION_UPDATE,
            'Siswa',
            $siswa->id,
            $desc,
            $oldData,
            $newData
        );
    }

    public function deleted(Siswa $siswa): void
    {
        if (!auth()->check()) {
            return;
        }

        ActivityLog::record(
            ActivityLog::ACTION_DELETE,
            'Siswa',
            $siswa->id,
            "Menghapus data siswa: {$siswa->nama}",
            $siswa->getOriginal(),
            null
        );
    }
}
