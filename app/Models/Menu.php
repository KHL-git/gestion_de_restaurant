<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    public const CATEGORIES = [
        'Entree' => 'Entree',
        'Plat principal' => 'Plat principal',
        'Dessert' => 'Dessert',
        'Boisson' => 'Boisson',
    ];

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'categorie',
        'disponible',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'disponible' => 'boolean',
        ];
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function saleLines(): HasMany
    {
        return $this->hasMany(SaleLine::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($term) {
            $builder
                ->where('nom', 'like', "%{$term}%")
                ->orWhere('categorie', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public static function categories(): array
    {
        return self::CATEGORIES;
    }

    public function imageUrl(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return Storage::disk('public')->url(ltrim($this->image, '/'));
    }

    public function formattedPrice(): string
    {
        return number_format((float) $this->prix, 0, ',', ' ').' FCFA';
    }
}
