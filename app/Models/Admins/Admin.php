<?php

namespace App\Models\Admins;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    
    protected $table = 'admins';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'store_id',
        'status',
    ];
    
    protected $hidden = [
        'password'
    ];

    public function isSuperAdmin(): bool
    {
        return ($this->role ?? '') === 'super_admin';
    }

    public function isStoreAdmin(): bool
    {
        return ($this->role ?? 'store_admin') === 'store_admin';
    }
}
