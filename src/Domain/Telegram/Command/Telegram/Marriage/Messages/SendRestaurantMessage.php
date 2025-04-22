<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class SendRestaurantMessage
{
    private const YANDEX_LINK = 'https://yandex.ru/maps/-/CHVLuZ1j';

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        try {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
                'text' => sprintf(
                    <<<'TXT'
                <b>Ресторан:</b>
                
                🍽️ Собираемся в ресторане по адресу:
                г. Краснодар, ул. Чапаева 86. <a href="%s?utm_source=telegram">(Ссылка на карту)</a>
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
