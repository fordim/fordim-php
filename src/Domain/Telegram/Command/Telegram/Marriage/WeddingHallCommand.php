<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendWeddingHallMessage;
use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Commands\Command;

final class WeddingHallCommand extends Command
{
    protected string $name = 'wedding_hall';
    protected string $description = 'Место проведение церимонии бракосочетания';

    public function __construct(
        private readonly AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private readonly AddTextLog $addTextLog,
        private readonly SendWeddingHallMessage $sendWeddingHallMessage,
        private readonly AddMenuButton $addMenuButton,
    ) {
    }

    public function handle(): void
    {
        $message = $this->getUpdate()->getMessage();
        $from = $this->getUpdate()->getMessage()->getFrom();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::wedding);
        $this->addTextLog->process($telegramUser, $message);

        $this->sendWeddingHallMessage->handleDirectly($this->telegram, $telegramUser);

        $this->addMenuButton->handleDirectly($this->telegram, $telegramUser);
    }
}
