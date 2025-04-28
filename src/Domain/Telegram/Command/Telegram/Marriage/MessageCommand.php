<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendContactsMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendDressCodeMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendKrasnodarInfoMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendRestaurantMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendWeddingHallMessage;
use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Type\CommandMessageType;
use App\Infrastructure\Doctrine\Entity\TelegramUser;
use App\Infrastructure\Repository\Doctrine\TelegramUser\TelegramUserRepository;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

final readonly class MessageCommand
{
    public function __construct(
        private AddTextLog $addTextLog,
        private TelegramUserRepository $telegramUserRepository,
        private SendRestaurantMessage $sendRestaurantMessage,
        private SendDressCodeMessage $sendDressCodeMessage,
        private SendWeddingHallMessage $sendWeddingHallMessage,
        private SendContactsMessage $sendContactsMessage,
        private SendKrasnodarInfoMessage $sendKrasnodarInfoMessage,
    ) {
    }

    /**
     * @throws TelegramSDKException
     */
    public function handle(Api $telegram, array $message): void
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? null;

        if ($text === null) {
            $messageText = 'Не отправляйте картинку';
            $this->sendDefaultMessage($telegram, $chatId, $messageText);

            return;
        }

        $telegramUser = $this->telegramUserRepository->findByChatId($chatId);

        if ($telegramUser === null && !str_starts_with($text, '/')) {
            $messageText = 'Добро пожаловать! Напиши в чате /start или воспользуйся меню бота и выбери там эту команду';
            $this->sendDefaultMessage($telegram, $chatId, $messageText);
            return;
        }

        $this->saveTextLog($telegramUser, $text);

        if (!str_starts_with($text, '/')) {
            switch ($text) {
                case CommandMessageType::restaurant->value: {
                    $this->sendRestaurantMessage->handleDirectly($telegram, $telegramUser);
                    break;
                }
                case CommandMessageType::dressCode->value: {
                    $this->sendDressCodeMessage->handleDirectly($telegram, $telegramUser);
                    break;
                }
                case CommandMessageType::weddingHall->value: {
                    $this->sendWeddingHallMessage->handleDirectly($telegram, $telegramUser);
                    break;
                }
                case CommandMessageType::contacts->value: {
                    $this->sendContactsMessage->handleDirectly($telegram, $telegramUser);
                    break;
                }
                case CommandMessageType::krasnodar->value: {
                    $this->sendKrasnodarInfoMessage->handleDirectly($telegram, $telegramUser);
                    break;
                }
                default:
                    $messageText = 'Такой команды нет: ' . $text;
                    $this->sendDefaultMessage($telegram, $chatId, $messageText);
            }
        }
    }

    private function saveTextLog(?TelegramUser $existUser, string $text): void
    {
        if ($existUser === null) {
            return;
        }

        $this->addTextLog->saveOnlyText($existUser, $text);
    }

    private function sendDefaultMessage(Api $telegram, int $chatId, string $messageText): void
    {
        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $messageText,
        ]);
    }
}
