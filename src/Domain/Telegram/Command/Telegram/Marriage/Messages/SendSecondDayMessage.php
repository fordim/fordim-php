<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

final readonly class SendSecondDayMessage
{
    private const picture = '/../../../../../../../public_html/images/second-day.png';

    private const YANDEX_LINK_SOK = 'https://bazaotdykhasok.ru/arbours';
    private const YANDEX_LINK_ADDRESS = 'https://yandex.ru/maps/-/CHvKfIlG';
    private const YANDEX_LINK_INFO = 'https://bazaotdykhasok.ru/pools';

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $imagePath = __DIR__ . self::picture;

        try {
            $telegram->sendPhoto([
                'chat_id' => $telegramUser->getChatId(),
                'photo' => InputFile::create(fopen($imagePath, 'rb'), 'second-day.png'),
                'parse_mode' => 'HTML',
                'caption' => sprintf(
                <<<'TXT'
                📌 Мы арендовали беседку для отдыха, шашлыков и общения.
                
                📍 <b>Место:</b> <a href="%s?utm_source=telegram">База отдыха «СОК»</a>
                <b>Адрес:</b> г. Краснодар, коттеджный посёлок «Бавария», ул. Староминская 38 (<a href="%s?utm_source=telegram">Ссылка на карту</a>)
                   
                🕚 <b>Время работы базы:</b> с 11:00 до 23:00
                <b>Время сборов мы отправим ближе к дате.</b>
                
                🏊‍ <b>Аквазона (по желанию)</b>
                На территории базы есть аквазона, если планируете её посещение - берите плавки, полотенца и удобную обувь. 
                🔹 Подробности и фото аквазоны <a href="%s?utm_source=telegram">по ссылке</a>
                🔹 Стоимость посещения аквазоны: <b>1300₽/взрослый и 1000₽/детский</b>
                Включает: подогреваемый бассейн, летний бассейн, финская сауна.
                🔹 Билеты приобретаются отдельно на входе (касса).
                TXT,
                    self::YANDEX_LINK_SOK,
                    self::YANDEX_LINK_ADDRESS,
                    self::YANDEX_LINK_INFO,
                ),
            ]);

            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'parse_mode' => 'HTML',
                'text' => sprintf(
                    <<<'TXT'
                    ​
                    🚫<b>ВАЖНО: Правила базы</b>
                    * Запрещено пользоваться бассейнами и сауной без оплаты.
                    * Запрещена <b>своя музыка</b> (портативные колонки и т. д.).
                    * Курение — только в специально отведённых местах.
                    * Нельзя проносить свою еду и напитки <b>на територию аквазоны</b>.
                    * Дети — под присмотром взрослых.
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
