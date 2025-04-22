<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Manual;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class SendNotificationMessage
{
    private const WEDDING_DATE = '2025-06-04';

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $daysLeft = $this->calculateDaysLeft();
        $message = $this->formatMessage($telegramUser->getUserName(), $daysLeft);

        $telegram->sendMessage([
            'chat_id' => $telegramUser->getChatId(),
            'text' => $message,
        ]);
    }

    private function calculateDaysLeft(): int
    {
        $weddingDate = new \DateTimeImmutable(self::WEDDING_DATE);
        $today = new \DateTimeImmutable('today');
        
        return $weddingDate->diff($today)->days;
    }

    private function formatMessage(string $userName, int $daysLeft): string
    {
        return sprintf(
            <<<'TXT'
            Привет, %s! 👋
            До нашей свадьбы осталось %d %s! 😇
            Мы очень ждем этого дня и надеемся, что вы разделите с нами нашу радость! 
            TXT,
            $userName,
            $daysLeft,
            $this->getDayWord($daysLeft)
        );
    }

    private function getDayWord(int $days): string
    {
        $lastDigit = $days % 10;
        $lastTwoDigits = $days % 100;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
            return 'дней';
        }

        return match ($lastDigit) {
            1 => 'день',
            2, 3, 4 => 'дня',
            default => 'дней',
        };
    }
}

