<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_PENDING = 'en attente';
    public const STATUS_CONFIRMED = 'confirmee';
    public const STATUS_CANCELLED = 'annulee';

    protected $fillable = [
        'user_id',
        'table_id',
        'total',
        'statut',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_CONFIRMED => 'Confirmee',
            self::STATUS_CANCELLED => 'Annulee',
        ];
    }

    public function statusLabel(): string
    {
        return self::statuses()[$this->statut] ?? ucfirst((string) $this->statut);
    }

    public function formattedTotal(): string
    {
        return number_format((float) $this->total, 0, ',', ' ').' FCFA';
    }

    public function itemsCount(): int
    {
        return (int) $this->items->sum('quantity');
    }

    public function itemsSummary(int $limit = 2): string
    {
        $names = $this->items
            ->filter(fn (OrderItem $item) => $item->menu)
            ->pluck('menu.nom')
            ->unique()
            ->values();

        if ($names->isEmpty()) {
            return 'Aucun plat';
        }

        $visible = $names->take($limit)->implode(', ');
        $remaining = $names->count() - min($limit, $names->count());

        return $remaining > 0 ? $visible.' +'.$remaining : $visible;
    }
}
