<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Type;

enum TelegramType: string
{
    case fordim = 'fordim';
    case wedding = 'wedding';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(static fn (self $type): string => $type->value, self::cases());
    }
}
