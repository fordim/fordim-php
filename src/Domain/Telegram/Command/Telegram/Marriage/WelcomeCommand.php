<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\FileUpload\InputFile;

final class WelcomeCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Приветсвенная команда';

    public function __construct(
        private readonly AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private readonly AddTextLog $addTextLog,
    ) {
    }

    public function handle(): void
    {
        $message = $this->getUpdate()->getMessage();
        $from = $this->getUpdate()->getMessage()->getFrom();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::wedding);
        $this->addTextLog->process($telegramUser, $message);

        $this->replyWithMessage([
            'text' => sprintf(
                <<<'TXT'
                Привет, %s!
                Добро пожаловать в свадебного бота 💍🎉
                Тут можно найти всю необходимую информацию о свадьбе Светланы и Дмитрия!
                TXT,
                $telegramUser->getUserName(),
            ),
        ]);

        $this->replyWithMessage([
            'text' => sprintf(
                <<<'TXT'
                📝 Используйте меню бота, что-бы получить какую-то конкретную информацию.
                TXT,
            ),
        ]);

        $imagePath = __DIR__ . '/../../../../../../public_html/images/colors.PNG';

        $this->replyWithPhoto([
            'chat_id' => 576623234,
            'photo' => InputFile::create(fopen($imagePath, 'rb'), 'colors.PNG'),
            'caption' => 'Так же просим ограничить яркие цвета, принты. Мы будем рады и благодарны, если своими нарядами вы поддержите цветовую гамму дня.',
            'parse_mode' => 'HTML',
        ]);
    }
}
