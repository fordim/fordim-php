<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

final readonly class SendContactsMessage
{
    private const picture = '/../../../../../../../public_html/images/contacts.png';

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $imagePath = __DIR__ . self::picture;

        try {
            $telegram->sendPhoto([
                'chat_id' => $telegramUser->getChatId(),
                'photo' => InputFile::create(fopen($imagePath, 'rb'), 'contacts.png'),
                'parse_mode' => 'HTML',
                'caption' => sprintf(
                <<<'TXT'
                👩🏼 <b>Координатор Юлия</b> +7918-996-25-28 
                Поможет со всеми организационными вопросами в день свадьбы. Найдёт вас, если потеряетесь, подскажет дорогу, встретит в ресторане. 
                
                🎤 <b>Ведущий Ростислав</b>  +7989-294-13-58
                Подскажет по всем вопросам касательно программы вечера. Поможет организовать сюрприз молодоженам, выделит время в программе под вашу задумку. 
                
                🤵🏼 <b>Жених</b> +7989-532-47-91 
                Можно звонить по всем вопросам. В день свадьбы 04.06 лучше не беспокоить с 14:00. 
                
                👰🏼‍ <b>Невеста</b> +7989-795-46-78
                Отвечает только в Telegram. В день свадьбы 04.06 лучше не беспокоить с 14:00. 
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
