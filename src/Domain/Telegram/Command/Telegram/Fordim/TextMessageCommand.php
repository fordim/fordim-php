<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Fordim;

use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Infrastructure\Repository\Doctrine\TelegramUser\TelegramUserRepository;

final readonly class TextMessageCommand
{
    public function __construct(
        private AddTextLog $addTextLog,
        private TelegramUserRepository $telegramUserRepository,
    ) {
    }

    public function handle(int $chatId, string $text): void
    {
        $existUser = $this->telegramUserRepository->findByChatId($chatId);

        if ($existUser === null) {
            return;
        }

        $this->addTextLog->saveOnlyText($existUser, $text);
    }
}
