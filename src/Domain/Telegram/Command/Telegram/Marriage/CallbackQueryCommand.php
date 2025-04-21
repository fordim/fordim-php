<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendWelcomeMessage;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\CallbackQuery;

final class CallbackQueryCommand
{
    public function __construct(
        private readonly AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private readonly SendWelcomeMessage $sendWelcomeMessage,
    ) {
    }

    public function handle(Api $telegram, CallbackQuery $callbackQuery): void
    {
        $data = $callbackQuery->getData();
        $message = $callbackQuery->getMessage();
        $chatId = $message->getChat()->getId();
        $from = $callbackQuery->getFrom();

        // Отправляем отладочное сообщение
        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Отладка: Получен callback_query с данными: ' . $data,
        ]);

        if ($data === 'start_welcome') {
            try {
                $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::wedding);
                
                $this->sendWelcomeMessage->handleDirectly($telegram, $telegramUser);

                // Отвечаем на callback, чтобы убрать "часики" на кнопке
                $telegram->answerCallbackQuery([
                    'callback_query_id' => $callbackQuery->getId(),
                ]);

                // Удаляем сообщение с кнопкой
                $telegram->deleteMessage([
                    'chat_id' => $chatId,
                    'message_id' => $message->getMessageId(),
                ]);
            } catch (\Exception $e) {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Отладка: Ошибка при обработке кнопки: ' . $e->getMessage(),
                ]);
            }
        } else {
            // Отправляем отладочное сообщение
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Отладка: Неизвестный callback_data: ' . $data,
            ]);
        }
    }
} 