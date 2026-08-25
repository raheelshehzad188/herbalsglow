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
        'access_token_encrypted',
        'connection_method',
        'status',
        'scopes',
        'last_synced_at',
    ];

    protected $hidden = [
        'access_token_encrypted',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
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

    public function isConnected(): bool
    {
        return $this->status === 'connected';
    }
}
