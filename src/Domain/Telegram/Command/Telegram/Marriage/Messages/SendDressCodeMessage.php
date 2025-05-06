<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

final readonly class SendDressCodeMessage
{
    private const picture = '/../../../../../../../public_html/images/dress-code.png';

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $imagePath = __DIR__ . self::picture;

        try {
            $telegram->sendPhoto([
                'chat_id' => $telegramUser->getChatId(),
                'photo' => InputFile::create(fopen($imagePath, 'rb'), 'dress-code.png'),
                'parse_mode' => 'HTML',
                'caption' => sprintf(
                <<<'TXT'
                Просим ограничить яркие цвета, принты. Мы будем рады и благодарны, если своими нарядами вы поддержите цветовую гамму дня.
                TXT,
                ),
            ]);
        } catch (\Exception $e) {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => 'Отладка: Ошибка при обработке кнопки: ' . $e->getMessage(),
            ]);
        }
    }
}
