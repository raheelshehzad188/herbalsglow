<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;

class ShopifyImportJob extends Model
{
    use BelongsToStore;

    protected $fillable = [
        'store_id',
        'connection_id',
        'status',
        'duplicate_mode',
        'config_json',
        'counts_json',
        'preview_json',
        'cursor_json',
        'cancel_requested',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'cancel_requested' => 'boolean',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function connection()
    {
        return $this->belongsTo(ShopifyConnection::class, 'connection_id');
    }

    public function errors()
    {
        return $this->hasMany(ShopifyImportError::class, 'job_id');
    }

    public function config(): array
    {
        $data = json_decode($this->config_json ?: '{}', true);
        return is_array($data) ? $data : [];
    }

    public function counts(): array
    {
        $data = json_decode($this->counts_json ?: '{}', true);
        return is_array($data) ? $data : [];
    }

    public function preview(): array
    {
        $data = json_decode($this->preview_json ?: '{}', true);
        return is_array($data) ? $data : [];
    }

    public function cursor(): array
    {
        $data = json_decode($this->cursor_json ?: '{}', true);
        return is_array($data) ? $data : [];
    }

    public function setConfig(array $config): void
    {
        $this->config_json = json_encode($config);
    }

    public function setCounts(array $counts): void
    {
        $this->counts_json = json_encode($counts);
    }

    public function setCursor(array $cursor): void
    {
        $this->cursor_json = json_encode($cursor);
    }
}
