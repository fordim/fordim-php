<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../../vendor/autoload.php';

use App\Domain\Telegram\Command\Telegram\Fordim\FinishCommand;
use App\Domain\Telegram\Command\Telegram\Fordim\StartCommand;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\HelpCommand;

$bot = new Api('7763662258:AAG72ZYqY3ljWwPl4Kp8ydSqLw-pOf09z6I');

$bot->addCommands([
    StartCommand::class,
    FinishCommand::class,
    HelpCommand::class,
]);

// Получаем входящие данные
$input = file_get_contents('php://input');
file_put_contents('webhook_log.txt', $input); // Логируем входящие данные

// Декодируем JSON
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Ошибка декодирования JSON: ' . json_last_error_msg());
}

// Проверяем, есть ли сообщение
if (isset($data['message'])) {
    $message = $data['message'];
    $chatId = $message['chat']['id'];
    $text = $message['text'];

    // Логируем данные о сообщении
    file_put_contents('message_log.txt', print_r($message, true));

    // Обрабатываем команды автоматически
    $bot->commandsHandler(true);

    // Если сообщение не является командой, отправляем эхо-ответ
    if (!str_starts_with($text, '/')) {
        $bot->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Вы сказали: ' . $text,
        ]);
    }
}
