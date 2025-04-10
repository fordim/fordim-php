<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Domain\Telegram\Command\Telegram\Fordim\FinishCommand;
use App\Domain\Telegram\Command\Telegram\Fordim\StartCommand;
use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;

final readonly class CommandFactory
{
    public function __construct(
        private AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private AddTextLog $addTextLog,
    ) {
    }

    public function createStartCommand(): StartCommand
    {
        return new StartCommand($this->addAndUpdateUserCommand, $this->addTextLog);
    }

    public function createFinishCommand(): FinishCommand
    {
        return new FinishCommand($this->addAndUpdateUserCommand, $this->addTextLog);
    }
}
