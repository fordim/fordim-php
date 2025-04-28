<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendRestaurantMessage;
use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Commands\Command;

final class RestaurantCommand extends Command
{
    protected string $name = 'restaurant';
    protected string $description = 'Место проведение банкета';

    public function __construct(
        private readonly AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private readonly AddTextLog $addTextLog,
        private readonly SendRestaurantMessage $sendRestaurantMessage,
    ) {
    }

    public function handle(): void
    {
        $message = $this->getUpdate()->getMessage();
        $from = $this->getUpdate()->getMessage()->getFrom();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::wedding);
        $this->addTextLog->process($telegramUser, $message);

        $this->sendRestaurantMessage->handleDirectly($this->telegram, $telegramUser);
    }
}
