<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class WooCommerceConnection extends Model
{
    use BelongsToStore;

    protected $table = 'woocommerce_connections';

    protected $fillable = [
        'store_id',
        'shop_url',
        'shop_host',
        'shop_name',
        'consumer_key',
        'consumer_secret_encrypted',
        'status',
        'last_connected_at',
        'last_synced_at',
    ];

    protected $hidden = [
        'consumer_key',
        'consumer_secret_encrypted',
    ];

    protected $casts = [
        'last_connected_at' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function setConsumerSecret(?string $plain): void
    {
        $this->consumer_secret_encrypted = $plain ? Crypt::encryptString($plain) : null;
    }

    public function getConsumerSecret(): string
    {
        if (!$this->consumer_secret_encrypted) {
            return '';
        }
        try {
            return Crypt::decryptString($this->consumer_secret_encrypted);
        } catch (\Throwable $e) {
            return '';
        }
    }

    public function isConnected(): bool
    {
        return $this->status === 'connected';
    }
}
