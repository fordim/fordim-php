<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class SendWeddingHallMessage
{
    private const YANDEX_LINK = 'https://yandex.ru/maps/-/CHVLqGkT';

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
                <b>Загс:</b>
                
                ⛪️ Брачная церимония состоятся по адресу:
                г. Краснодар, ул. Офицерская 47. <a href="%s">(Ссылка на карту)</a>
                Всех гостей ждем у основного входа в главный Екатерининский зал.
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
