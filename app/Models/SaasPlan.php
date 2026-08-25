<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasPlan extends Model
{
    protected $table = 'saas_plans';
    protected $fillable = [
        'name', 'slug', 'audience', 'price_label', 'price_amount',
        'highlight', 'features', 'button_text', 'sort', 'status',
    ];
    protected $casts = ['highlight' => 'boolean', 'status' => 'boolean'];

    public function featureList(): array
    {
        $raw = trim((string) $this->features);
        if ($raw === '') {
            return [];
        }
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\n|\r/', $raw))));
    }
}
