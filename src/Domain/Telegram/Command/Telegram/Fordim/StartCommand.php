<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Fordim;

use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Start command to get started with the bot';

    public function __construct(
        private AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private AddTextLog $addTextLog,
    ) {
    }

    public function handle(): void
    {
        $message = $this->getUpdate()->getMessage();
        $from = $this->getUpdate()->getMessage()->getFrom();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::fordim);
        $this->addTextLog->process($telegramUser, $message);

        $this->replyWithMessage([
            'text' => "Привет, {$telegramUser->getUserName()}! Добро пожаловать в бота!",
        ]);
    }
}
