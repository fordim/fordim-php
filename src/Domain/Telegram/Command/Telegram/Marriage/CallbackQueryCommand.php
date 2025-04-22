<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendContactsMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendDressCodeMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendRestaurantMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendWeddingHallMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendWelcomeMessage;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\CallbackQuery;

final readonly class CallbackQueryCommand
{
    public function __construct(
        private AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private SendWelcomeMessage $sendWelcomeMessage,
        private SendDressCodeMessage $sendDressCodeMessage,
        private SendRestaurantMessage $sendRestaurantMessage,
        private SendWeddingHallMessage $sendWeddingHallMessage,
        private SendContactsMessage $sendContactsMessage,
        private AddFullMenu $addFullMenu,
        private AddMenuButton $addMenuButton,
    ) {
    }

    public function handle(Api $telegram, CallbackQuery $callbackQuery): void
    {
        $data = $callbackQuery->getData();
        $message = $callbackQuery->getMessage();
        $chatId = $message->getChat()->getId();
        $from = $callbackQuery->getFrom();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::wedding);

        $telegram->answerCallbackQuery([
            'callback_query_id' => $callbackQuery->getId(),
        ]);

        $telegram->deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $message->getMessageId(),
        ]);

        match ($data) {
            'start_welcome' => $this->sendWelcomeMessage->handleDirectly($telegram, $telegramUser),
            'dress_code' => $this->sendDressCodeMessage->handleDirectly($telegram, $telegramUser),
            'restaurant' => $this->sendRestaurantMessage->handleDirectly($telegram, $telegramUser),
            'wedding_hall' => $this->sendWeddingHallMessage->handleDirectly($telegram, $telegramUser),
            'contacts' => $this->sendContactsMessage->handleDirectly($telegram, $telegramUser),
            'menu' => $this->addFullMenu->handleDirectly($telegram, $telegramUser),
            default => null,
        };

        if ($data !== 'menu') {
            $this->addMenuButton->handleDirectly($telegram, $telegramUser);
        }
    }
} 