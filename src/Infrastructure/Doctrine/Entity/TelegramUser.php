<?php

namespace App\Infrastructure\Doctrine\Entity;

use App\Domain\Telegram\Type\TelegramType;
use App\Infrastructure\Repository\Doctrine\TelegramUser\TelegramUserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TelegramUserRepository::class)]
#[ORM\Table(name: 'telegram_users')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(columns: ['chat_id'], name: 'telegram_users_chat_id_idx')]
#[ORM\Index(columns: ['is_active'], name: 'telegram_users_active_idx')]
#[ORM\Index(columns: ['telegram_type'], name: 'telegram_users_telegram_type')]
class TelegramUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(name: 'chat_id', type: 'bigint')]
    private int $chatId;

    #[ORM\Column(name: 'user_name', length: 255)]
    private string $userName ;

    #[ORM\Column(name: 'user_surname', length: 255, nullable: true)]
    private ?string $userSurname = null;

    #[ORM\Column(name: 'nickname', length: 255)]
    #[Assert\Length(max: 255)]
    private string $nickname;

    #[ORM\Column(name: 'language_code', length: 10)]
    private string $languageCode;

    #[ORM\Column(name: 'is_bot', type: 'boolean', options: ['default' => false])]
    private bool $isBot = false;

    #[ORM\Column(name: 'is_active', type: 'boolean', options: ['default' => true])]
    private bool $isActive;

    #[ORM\Column(name: 'last_interaction', type: 'datetime_immutable')]
    private \DateTimeImmutable $lastInteraction;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'telegram_type', enumType: TelegramType::class)]
    #[Assert\Choice(callback: [TelegramType::class, 'cases'])]
    private TelegramType $telegramType;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function setChatId(int $chatId): self
    {
        $this->chatId = $chatId;
        return $this;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    public function getUserSurname(): ?string
    {
        return $this->userSurname;
    }

    public function setUserSurname(?string $userSurname): self
    {
        $this->userSurname = $userSurname;
        return $this;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;
        return $this;
    }

    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    public function setLanguageCode(string $languageCode): self
    {
        $this->languageCode = $languageCode;
        return $this;
    }

    public function isBot(): bool
    {
        return $this->isBot;
    }

    public function setIsBot(bool $isBot): self
    {
        $this->isBot = $isBot;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getLastInteraction(): \DateTimeImmutable
    {
        return $this->lastInteraction;
    }

    public function setLastInteraction(\DateTimeImmutable $lastInteraction): self
    {
        $this->lastInteraction = $lastInteraction;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $now = new \DateTimeImmutable();

        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->lastInteraction = $now;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getTelegramType(): TelegramType
    {
        return $this->telegramType;
    }

    public function setTelegramType(TelegramType $telegramType): self
    {
        $this->telegramType = $telegramType;
        return $this;
    }
}
