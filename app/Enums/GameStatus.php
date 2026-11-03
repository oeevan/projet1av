<?php

namespace App\Enums;

enum GameStatus: string
{
    case Released = 'released';
    case Upcoming = 'upcoming';
    case EarlyAccess = 'early_access';

    /**
     * Libellé affiché dans les vues.
     */
    public function label(): string
    {
        return match ($this) {
            self::Released => 'Disponible',
            self::Upcoming => 'À venir',
            self::EarlyAccess => 'Accès anticipé',
        };
    }
}
