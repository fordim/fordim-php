<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendContactsMessage;
use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Commands\Command;

final class ContactsCommand extends Command
{
    protected string $name = 'contacts';
    protected string $description = 'Контакты которые могут пригодится';

    public function __construct(
        private readonly AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private readonly AddTextLog $addTextLog,
        private readonly SendContactsMessage $sendContactsMessage,
    ) {
    }

    public function handle(): void
    {
        $message = $this->getUpdate()->getMessage();
        $from = $this->getUpdate()->getMessage()->getFrom();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::wedding);
        $this->addTextLog->process($telegramUser, $message);

        $this->sendContactsMessage->handleDirectly($this->telegram, $telegramUser);
    }
}
