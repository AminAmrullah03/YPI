<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    const UPDATED_AT = null; // only created_at

    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    const ACTION_LABELS = [
        'create' => 'Tambah Data',
        'update' => 'Ubah Data',
        'delete' => 'Hapus Data',
    ];

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'description',
        'old_data',
        'new_data',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'old_data' => 'array',
            'new_data' => 'array',
        ];
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getActionLabelAttribute(): string
    {
        return self::ACTION_LABELS[$this->action] ?? $this->action;
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Static Helper ───────────────────────────────────────────────────────

    public static function record(
        string $action,
        string $model,
        int $modelId,
        string $description,
        ?array $oldData = null,
        ?array $newData = null
    ): void {
        static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model'       => $model,
            'model_id'    => $modelId,
            'description' => $description,
            'old_data'    => $oldData,
            'new_data'    => $newData,
            'ip_address'  => request()->ip(),
        ]);
    }
}
