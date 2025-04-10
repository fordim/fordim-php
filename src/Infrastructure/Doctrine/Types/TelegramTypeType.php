<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TelegramTypeType extends Type
{
    public const NAME = 'telegram_type';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(20)';
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
