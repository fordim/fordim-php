<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class SendKrasnodarInfoMessage
{
    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        try {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'parse_mode' => 'HTML',
                'text' => sprintf(
                    <<<'TXT'
                <b>Информация по Краснодару:</b>
                
                📍 Адрес молодожёнов:
                - ул. Жлобы, 141, подъезд 2
                
                🛒 Ближайшие магазины и ТЦ:
                - ТЦ «СБС» (5 км) – большой выбор магазинов и кафе.
                - ТЦ «Галерея» (7 км) – супермаркеты, бутики. Центр Города.
                - Магнит, Пятёрочка – рядом с домом.

                🌳 Парки и места для прогулок:
                - Парк Галицкого (Краснодар), основной парк для прогулок (рядом с домом молодежёнов).
                - Парк «Солнечный остров».

                🏨 Где остановиться?
                - Рядом с домом молодожёнов очень много квартир и апартаментов по суточно. Если нужна какая-то помощь с поиском и бронированием, пишите.
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
