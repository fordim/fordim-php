<?php

namespace App\Infrastructure\Doctrine\Entity;

use App\Infrastructure\Repository\Doctrine\TelegramTextLog\TelegramTextLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TelegramTextLogRepository::class)]
#[ORM\Table(name: 'telegram_text_logs')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(columns: ['chat_id'], name: 'telegram_text_log_chat_id_idx')]
#[ORM\Index(columns: ['user_id'], name: 'telegram_text_log_user_id_idx')]
class TelegramTextLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(name: 'chat_id', type: 'bigint')]
    private int $chatId;

    #[ORM\Column(name: 'user_id', type: 'bigint')]
    private int $userId;

    #[ORM\Column(name: 'message', type: 'text', length: 1000, nullable: false)]
    private string $message ;

    #[ORM\Column(name: 'send_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $sendAt;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

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

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getSendAt(): \DateTimeImmutable
    {
        return $this->sendAt;
    }

    public function setSendAt(\DateTimeImmutable $sendAt): self
    {
        $this->sendAt = $sendAt;
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
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
