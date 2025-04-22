<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

final readonly class SendVideoMessage
{
    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $videoPath = __DIR__ . '/../../../../../../../public_html/videos/welcome.mp4';

        $telegram->sendVideo([
            'chat_id' => $telegramUser->getChatId(),
            'video' => InputFile::create(fopen($videoPath, 'rb'), 'welcome.mp4'),
            'caption' => 'Добро пожаловать на нашу свадьбу!',
            'parse_mode' => 'HTML',
        ]);
    }
}

