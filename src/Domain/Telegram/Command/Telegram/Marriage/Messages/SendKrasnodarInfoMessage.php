<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

final readonly class SendKrasnodarInfoMessage
{
    private const picture = '/../../../../../../../public_html/images/krasnodar.png';

    private const YANDEX_LINK_HOME = 'https://yandex.ru/maps/35/krasnodar/?ll=39.031725%2C45.046666&utm_source=telegram&z=18';

    private const YANDEX_LINK_SBS = 'https://yandex.ru/maps/-/CHrZQAm0';

    private const YANDEX_LINK_GALLERY = 'https://yandex.ru/maps/-/CHrZQBnu';

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $imagePath = __DIR__ . self::picture;

        try {
            $telegram->sendPhoto([
                'chat_id' => $telegramUser->getChatId(),
                'photo' => InputFile::create(fopen($imagePath, 'rb'), 'krasnodar.png'),
                'parse_mode' => 'HTML',
                'caption' => sprintf(
                <<<'TXT'
                📍 Адрес молодожёнов:
                Ул. Жлобы, 141, подъезд 2 <a href="%s?utm_source=telegram">(Ссылка на карту)</a>
                
                🏢Где остановиться?
                Мы рекомендуем останавливаться в районе, где живут молодожёны. Так как это туристический центр и  в соседних домах сдаётся много посуточных квартир-апартаментов. Пишите молодоженам, если нужна помощь с поиском жилья в этом районе. 
                
                🛒 Ближайшие магазины и ТЦ
                <b>В районе есть</b>: перекрёсток, пятерочки, магниты, К&Б, кофейни, столовые.
                <b>ТЦ «СБС»</b> (5 км, <a href="%s?utm_source=telegram">ссылка на карте</a>) – большой выбор различных магазинов и кафе, кинотеатр, фуд-корт.
                <b>ТЦ «Галерея»</b> (7 км,  <a href="%s?utm_source=telegram">ссылка на карте</a>) – находится на центральной улице. Большой выбор магазинов одежды, кинотеатр, фуд-корт. Есть большой магазин «Перекрёсток».
                
                🌳Где погулять? 
                <b>Парк «Краснодар» (Парк Галицкого)</b> — современный парк с уникальным ландшафтным дизайном, японским садом и различными зонами для отдыха и развлечений.
                <b>Парк «Солнечный остров»</b> — расположен на берегу реки Кубань, предлагает прогулки среди вековых деревьев, зоопарк, планетарий и различные аттракционы.
                TXT,
                    self::YANDEX_LINK_HOME,
                    self::YANDEX_LINK_SBS,
                    self::YANDEX_LINK_GALLERY,
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
