<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Domain\Telegram\Command\Telegram\Fordim\FinishCommand;
use App\Domain\Telegram\Command\Telegram\Fordim\StartCommand;
use App\Domain\Telegram\Command\Telegram\Marriage\AddFullMenu;
use App\Domain\Telegram\Command\Telegram\Marriage\AddKeyboardMenu;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendWelcomeMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\WelcomeCommand;
use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;

final readonly class CommandFactory
{
    public function __construct(
        private AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private AddTextLog $addTextLog,
        private SendWelcomeMessage $sendWelcomeMessage,
        private AddFullMenu $addFullMenu,
        private AddKeyboardMenu $addKeyboardMenu,
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

    public function createWelcomeCommand(): WelcomeCommand
    {
        return new WelcomeCommand(
            $this->addAndUpdateUserCommand,
            $this->addTextLog,
            $this->sendWelcomeMessage,
            $this->addFullMenu,
            $this->addKeyboardMenu,
        );
    }
}
