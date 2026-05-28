<?php

namespace App\Observers;

use App\Models\Guru;
use App\Models\ActivityLog;

class GuruObserver
{
    public function created(Guru $guru): void
    {
        if (!auth()->check()) {
            return;
        }

        ActivityLog::record(
            ActivityLog::ACTION_CREATE,
            'Guru',
            $guru->id,
            "Menambahkan guru baru: {$guru->nama} (" . ($guru->lembaga?->jenis_label ?? '-') . ")",
            null,
            $guru->getAttributes()
        );
    }

    public function updated(Guru $guru): void
    {
        if (!auth()->check()) {
            return;
        }

        $changes = $guru->getChanges();
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        $oldData = array_intersect_key($guru->getOriginal(), $changes);
        $newData = $changes;

        $desc = "Mengubah data guru: {$guru->nama}";
        if (isset($changes['status'])) {
            $oldStatus = Guru::STATUS_LIST[$guru->getOriginal('status')] ?? $guru->getOriginal('status');
            $newStatus = Guru::STATUS_LIST[$guru->status] ?? $guru->status;
            $desc = "Mengubah status guru {$guru->nama} dari '{$oldStatus}' menjadi '{$newStatus}'";
        }

        ActivityLog::record(
            ActivityLog::ACTION_UPDATE,
            'Guru',
            $guru->id,
            $desc,
            $oldData,
            $newData
        );
    }

    public function deleted(Guru $guru): void
    {
        if (!auth()->check()) {
            return;
        }

        ActivityLog::record(
            ActivityLog::ACTION_DELETE,
            'Guru',
            $guru->id,
            "Menghapus data guru: {$guru->nama}",
            $guru->getOriginal(),
            null
        );
    }
}
