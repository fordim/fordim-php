<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Type;

enum MessageType: string
{
    case notification = 'notification';
    case video = 'video';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(static fn (self $type): string => $type->value, self::cases());
    }
}
