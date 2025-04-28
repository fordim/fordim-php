<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendContactsMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendDressCodeMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendKrasnodarInfoMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendRestaurantMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendWeddingHallMessage;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\CallbackQuery;

final readonly class CallbackQueryCommand
{
    private const CALLBACK_DATA = [
        'dress-code' => 'dress-code',
        'restaurant' => 'restaurant',
        'wedding-hall' => 'wedding-hall',
        'contacts' => 'contacts',
        'krasnodar' => 'krasnodar',
    ];

    public function __construct(
        private AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private SendDressCodeMessage $sendDressCodeMessage,
        private SendRestaurantMessage $sendRestaurantMessage,
        private SendWeddingHallMessage $sendWeddingHallMessage,
        private SendContactsMessage $sendContactsMessage,
        private SendKrasnodarInfoMessage $sendKrasnodarInfoMessage,
    ) {
    }

    public function handle(Api $telegram, CallbackQuery $callbackQuery): void
    {
        $data = $callbackQuery->getData();
        $message = $callbackQuery->getMessage();
        $chatId = $message->getChat()->getId();
        $from = $callbackQuery->getFrom();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::wedding);

        try {
            $telegram->answerCallbackQuery([
                'callback_query_id' => $callbackQuery->getId(),
            ]);

            $telegram->deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $message->getMessageId(),
            ]);

            $this->handleMessageType($telegram, $telegramUser, $data);
        } catch (\Exception $e) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Произошла ошибка при обработке запроса. Пожалуйста, попробуйте позже.',
            ]);
        }
    }

    private function handleMessageType(Api $telegram, TelegramUser $telegramUser, string $data): void
    {
        match ($data) {
            self::CALLBACK_DATA['dress-code'] => $this->sendDressCodeMessage->handleDirectly($telegram, $telegramUser),
            self::CALLBACK_DATA['restaurant'] => $this->sendRestaurantMessage->handleDirectly($telegram, $telegramUser),
            self::CALLBACK_DATA['wedding-hall'] => $this->sendWeddingHallMessage->handleDirectly($telegram, $telegramUser),
            self::CALLBACK_DATA['contacts'] => $this->sendContactsMessage->handleDirectly($telegram, $telegramUser),
            self::CALLBACK_DATA['krasnodar'] => $this->sendKrasnodarInfoMessage->handleDirectly($telegram, $telegramUser),
            default => null,
        };
    }
} 