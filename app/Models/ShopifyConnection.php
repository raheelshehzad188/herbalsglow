<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ShopifyConnection extends Model
{
    use BelongsToStore;

    protected $fillable = [
        'store_id',
        'shop_domain',
        'shop_name',
        'shopify_shop_id',
        'client_id',
        'client_secret_encrypted',
        'access_token_encrypted',
        'token_expires_at',
        'connection_method',
        'status',
        'scopes',
        'refresh_token_encrypted',
        'installed_at',
        'last_connected_at',
        'last_synced_at',
    ];

    protected $hidden = [
        'access_token_encrypted',
        'refresh_token_encrypted',
        'client_secret_encrypted',
        'client_id',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
        'installed_at' => 'datetime',
        'last_connected_at' => 'datetime',
        'token_expires_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function setAccessToken(string $plain): void
    {
        $this->access_token_encrypted = Crypt::encryptString($plain);
    }

    public function getAccessToken(): string
    {
        if (!$this->access_token_encrypted) {
            return '';
        }
        return Crypt::decryptString($this->access_token_encrypted);
    }

    public function setRefreshToken(?string $plain): void
    {
        $this->refresh_token_encrypted = $plain ? Crypt::encryptString($plain) : null;
    }

    public function setClientSecret(?string $plain): void
    {
        $this->client_secret_encrypted = $plain ? Crypt::encryptString($plain) : null;
    }

    public function getClientSecret(): string
    {
        if (!$this->client_secret_encrypted) {
            return '';
        }
        try {
            return Crypt::decryptString($this->client_secret_encrypted);
        } catch (\Throwable $e) {
            return '';
        }
    }

    public function isConnected(): bool
    {
        return $this->status === 'connected';
    }
}
