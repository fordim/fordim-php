<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Commands\Command;

class WeddingHallCommand extends Command
{
    protected string $name = 'wedding_hall';
    protected string $description = 'Место проведение церимонии бракосочетания';

    public function __construct(
        private AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private AddTextLog $addTextLog,
    ) {
    }

    public function handle(): void
    {
        $message = $this->getUpdate()->getMessage();
        $from = $this->getUpdate()->getMessage()->getFrom();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::wedding);
        $this->addTextLog->process($telegramUser, $message);

        $this->replyWithMessage([
            'text' => "Брачная церимония состоятся по адресу ",
        ]);
    }
}
