<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../../vendor/autoload.php';

use App\Domain\Telegram\Command\Telegram\Marriage\RestaurantCommand;
use App\Domain\Telegram\Command\Telegram\Marriage\StartCommand;
use App\Domain\Telegram\Command\Telegram\Marriage\WeddingHallCommand;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\HelpCommand;

$bot = new Api('7634771753:AAFgwChqR8ITNFIB2JgWIZABi9mG_wr6dAQ');

$bot->addCommands([
    StartCommand::class,
    RestaurantCommand::class,
    WeddingHallCommand::class,
    HelpCommand::class,
]);

// Получаем входящие данные
$input = file_get_contents('php://input');
file_put_contents('wedding_log.txt', $input);

// Декодируем JSON
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Ошибка декодирования JSON: ' . json_last_error_msg());
}

if (isset($data['message'])) {
    $message = $data['message'];
    $chatId = $message['chat']['id'];
    $text = $message['text'];

    file_put_contents('wedding_message_log.txt', print_r($message, true));

    $bot->commandsHandler(true);

    if (!str_starts_with($text, '/')) {
        $bot->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Вы сказали: ' . $text,
        ]);
    }
}
