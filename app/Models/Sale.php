<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    public const STATUS_PENDING = 'en_attente';
    public const STATUS_PAID = 'payee';
    public const STATUS_CANCELLED = 'annulee';

    public const PAYMENT_METHOD_CASH = 'especes';
    public const PAYMENT_METHOD_MOBILE = 'mobile_money';
    public const PAYMENT_METHOD_CARD = 'carte';
    public const PAYMENT_METHOD_TRANSFER = 'virement';

    protected $fillable = [
        'reference',
        'menu_id',
        'user_id',
        'client_name',
        'quantity',
        'unit_price',
        'total',
        'payment_method',
        'status',
        'sold_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'total' => 'decimal:2',
            'sold_at' => 'datetime',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(SaleLine::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($term) {
            $builder
                ->where('reference', 'like', "%{$term}%")
                ->orWhere('client_name', 'like', "%{$term}%")
                ->orWhere('payment_method', 'like', "%{$term}%")
                ->orWhere('status', 'like', "%{$term}%")
                ->orWhereHas('menu', function (Builder $menuQuery) use ($term) {
                    $menuQuery->where('nom', 'like', "%{$term}%");
                })
                ->orWhereHas('lines.menu', function (Builder $menuQuery) use ($term) {
                    $menuQuery->where('nom', 'like', "%{$term}%");
                })
                ->orWhereHas('user', function (Builder $userQuery) use ($term) {
                    $userQuery
                        ->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
        });
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_PAID => 'Payee',
            self::STATUS_CANCELLED => 'Annulee',
        ];
    }

    public static function paymentMethods(): array
    {
        return [
            self::PAYMENT_METHOD_CASH => 'Especes',
            self::PAYMENT_METHOD_MOBILE => 'Mobile Money',
            self::PAYMENT_METHOD_CARD => 'Carte bancaire',
            self::PAYMENT_METHOD_TRANSFER => 'Virement',
        ];
    }

    public function statusLabel(): string
    {
        return self::statuses()[$this->status] ?? ucfirst((string) $this->status);
    }

    public function paymentMethodLabel(): string
    {
        return self::paymentMethods()[$this->payment_method] ?? ucfirst((string) $this->payment_method);
    }

    public function customerLabel(): string
    {
        if ($this->client_name) {
            return $this->client_name;
        }

        return $this->user?->name ?? 'Client occasionnel';
    }

    public function formattedUnitPrice(): string
    {
        return number_format((float) $this->unit_price, 0, ',', ' ').' FCFA';
    }

    public function formattedTotal(): string
    {
        return number_format((float) $this->total, 0, ',', ' ').' FCFA';
    }

    public function itemsCount(): int
    {
        if ($this->relationLoaded('lines') && $this->lines->isNotEmpty()) {
            return (int) $this->lines->sum('quantity');
        }

        return (int) $this->quantity;
    }

    public function itemsSummary(int $limit = 2): string
    {
        $names = $this->lines
            ->filter(fn (SaleLine $line) => $line->menu)
            ->pluck('menu.nom')
            ->unique()
            ->values();

        if ($names->isEmpty() && $this->menu) {
            return $this->menu->nom;
        }

        if ($names->isEmpty()) {
            return 'Aucun plat';
        }

        $visible = $names->take($limit)->implode(', ');
        $remaining = $names->count() - min($limit, $names->count());

        return $remaining > 0 ? $visible.' +'.$remaining : $visible;
    }
}