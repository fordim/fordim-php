<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

final readonly class SendWelcomeMessage
{
    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        try {
            // Отправляем приветственное сообщение
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => sprintf(
                    <<<'TXT'
                    Привет, %s!
                    Добро пожаловать в свадебного бота 💍🎉
                    Тут можно найти всю необходимую информацию о свадьбе Светланы и Дмитрия!
                    TXT,
                    $telegramUser->getUserName(),
                ),
            ]);

            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => sprintf(
                    <<<'TXT'
                    📝 Используйте меню бота, что-бы получить какую-то конкретную информацию.
                    TXT,
                ),
            ]);

            $imagePath = __DIR__ . '/../../../../../../../public_html/images/colors.PNG';

            $telegram->sendPhoto([
                'chat_id' => $telegramUser->getChatId(),
                'photo' => InputFile::create(fopen($imagePath, 'rb'), 'colors.PNG'),
                'caption' => 'Так же просим ограничить яркие цвета, принты. Мы будем рады и благодарны, если своими нарядами вы поддержите цветовую гамму дня.',
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            // Отправляем сообщение об ошибке
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => 'Отладка: Ошибка при обработке кнопки: ' . $e->getMessage(),
            ]);
        }
    }
}
