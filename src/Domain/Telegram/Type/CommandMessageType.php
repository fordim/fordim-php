<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Type;

enum CommandMessageType: string
{
    case restaurant = '🍽️ Ресторан';
    case dressCode = '👗 Дресс-код';
    case weddingHall = '⛪️ Загс';
    case contacts = '📲 Контанты';
    case krasnodar = '🌴 Краснодар';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(static fn (self $type): string => $type->value, self::cases());
    }
}
