<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

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
    ) {
    }

    public function handle(): void
    {
        $message = $this->getUpdate()->getMessage();
        $from = $this->getUpdate()->getMessage()->getFrom();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::wedding);
        $this->addTextLog->process($telegramUser, $message);

        $this->replyWithMessage([
            'parse_mode' => 'HTML',
            'text' => sprintf(
                <<<'TXT'
                - <b>Невеста</b> - тел. +7989-532-47-91
                - <b>Жених</b> - тел. +7989-795-46-78
                - <b>Координатор Юлия</b> - тел. +7918-996-25-28 (все вопросы связанные с непосредсвенно мероприятием в ресторане: как доехать, как пройти, куда садиться и тд.)
                - <b>Ведущий Ростислав</b> - +7989-294-13-58 (все вопросы связанные с программой, возможно что-то свое хотите добавить, можно согласовать с ведущим, он поможет организовать)
                TXT,
            ),
        ]);
    }
}
