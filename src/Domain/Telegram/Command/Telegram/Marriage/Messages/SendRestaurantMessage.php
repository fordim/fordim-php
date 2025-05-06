<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

final readonly class SendRestaurantMessage
{
    private const picture = '/../../../../../../../public_html/images/restaurant.png';

    private const YANDEX_LINK = 'https://yandex.ru/maps/-/CHVLuZ1j';

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $imagePath = __DIR__ . self::picture;

        try {
            $telegram->sendPhoto([
                'chat_id' => $telegramUser->getChatId(),
                'photo' => InputFile::create(fopen($imagePath, 'rb'), 'restaurant.png'),
                'parse_mode' => 'HTML',
                'caption' => sprintf(
                <<<'TXT'
                Наш праздничный ужин пройдёт в ресторане <b>Central Park 22</b>🌿
                
                Адрес: г. Краснодар, ул. Чапаева 86. <a href="%s?utm_source=telegram">(Ссылка на карту)</a>

                ⏰Сбор в ресторане в 17:00
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
