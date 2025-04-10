<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\TelegramUser;

use App\Domain\Telegram\Type\TelegramType;
use App\Infrastructure\Doctrine\Entity\TelegramUser;
use App\Infrastructure\Repository\Doctrine\TelegramUser\TelegramUserRepository;
use Telegram\Bot\Objects\User;

final readonly class AddAndUpdateUserCommand
{
    public function __construct(
        private TelegramUserRepository $telegramUserRepository,
    ) {
    }

    public function process(User $user, TelegramType $telegramType): TelegramUser
    {
        $now = new \DateTimeImmutable();
        $existUser = $this->telegramUserRepository->findByChatId($user->getId());

        if ($existUser !== null) {
            $existUser->setLastInteraction($now);

            $this->telegramUserRepository->save($existUser);
            return $existUser;
        }

        $telegramUser = new TelegramUser();
        $telegramUser
            ->setChatId($user->getId())
            ->setUserName($user->getFirstName())
            ->setUserSurname($user->getLastName() ?? null)
            ->setNickname($user->getUsername())
            ->setLanguageCode($user->getLanguageCode())
            ->setIsBot($user->isBot())
            ->setIsActive(true)
            ->setTelegramType($telegramType)
        ;

        $this->telegramUserRepository->save($telegramUser);

        return $telegramUser;
    }
}
