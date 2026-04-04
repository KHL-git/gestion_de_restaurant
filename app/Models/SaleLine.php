<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleLine extends Model
{
    protected $fillable = [
        'sale_id',
        'menu_id',
        'quantity',
        'unit_price',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function formattedUnitPrice(): string
    {
        return number_format((float) $this->unit_price, 0, ',', ' ').' FCFA';
    }

    public function formattedTotal(): string
    {
        return number_format((float) $this->total, 0, ',', ' ').' FCFA';
    }
}