<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Commands\Command;

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
                Добро пожаловать в свадебного бота 💍, тут можно найти всю необходимую информацию о свадьбе!
                Используйте меню, что-бы получить какую-то конкретную информацию 📝.
                TXT,
                $telegramUser->getUserName(),
            ),
        ]);
    }
}
