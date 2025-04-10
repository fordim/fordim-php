<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\TelegramTextLog;

use App\Infrastructure\Doctrine\Entity\TelegramTextLog;
use App\Infrastructure\Doctrine\Entity\TelegramUser;
use App\Infrastructure\Repository\Doctrine\TelegramTextLog\TelegramTextLogRepository;
use DateTimeImmutable;
use Illuminate\Support\Collection;

final readonly class AddTextLog
{
    public function __construct(
        private TelegramTextLogRepository $telegramTextLogRepository,
    ) {
    }

    public function process(TelegramUser $telegramUser, Collection $message): void
    {
        $newLog = new TelegramTextLog();
        $newLog
            ->setChatId($telegramUser->getChatId())
            ->setUserId($telegramUser->getId())
            ->setMessage($message->getText())
            ->setSendAt(DateTimeImmutable::createFromFormat('U', (string) $message->getDate()))
        ;

        $this->telegramTextLogRepository->save($newLog);
    }

    public function saveOnlyText(TelegramUser $telegramUser, string $text): void
    {
        $newLog = new TelegramTextLog();
        $newLog
            ->setChatId($telegramUser->getChatId())
            ->setUserId($telegramUser->getId())
            ->setMessage($text)
            ->setSendAt(new DateTimeImmutable())
        ;

        $this->telegramTextLogRepository->save($newLog);
    }
}
