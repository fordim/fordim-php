<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

final readonly class SendWeddingHallMessage
{
    private const picture = '/../../../../../../../public_html/images/wedding-hall.png';

    private const YANDEX_LINK = 'https://yandex.ru/maps/-/CHVLqGkT';

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $imagePath = __DIR__ . self::picture;

        try {
            $telegram->sendPhoto([
                'chat_id' => $telegramUser->getChatId(),
                'photo' => InputFile::create(fopen($imagePath, 'rb'), 'wedding-hall.png'),
                'parse_mode' => 'HTML',
                'caption' => sprintf(
                <<<'TXT'
                Роспись молодоженов состоится в <b>16:00</b>
                
                ⛪️ Дворец бракосочетания по адресу:
                г. Краснодар, ул. Офицерская 47. <a href="%s">(Ссылка на карту)</a>
                
                Всех гостей ждем у основного входа в главный Екатерининский зал.
                ⏰В 15:30
                TXT,
                    self::YANDEX_LINK,
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
