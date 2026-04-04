<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    public const STATUS_PENDING = 'en attente';
    public const STATUS_CONFIRMED = 'confirmee';
    public const STATUS_CANCELLED = 'annulee';

    protected $fillable = [
        'user_id',
        'table_id',
        'date_reservation',
        'nombre_personnes',
        'statut',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_reservation' => 'datetime',
            'nombre_personnes' => 'integer',
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

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class);
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

    public function menusSummary(int $limit = 2): string
    {
        $names = $this->menus->pluck('nom');

        if ($names->isEmpty()) {
            return 'Aucun plat sélectionné';
        }

        $summary = $names->take($limit)->implode(', ');

        return $names->count() > $limit
            ? $summary.' +'.($names->count() - $limit)
            : $summary;
    }
}
