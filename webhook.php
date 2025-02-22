<?php

require __DIR__ . '/vendor/autoload.php';

use Telegram\Bot\Api;

// Вставьте сюда ваш токен
$bot = new Api('7763662258:AAG72ZYqY3ljWwPl4Kp8ydSqLw-pOf09z6I');

// Получаем входящие данные
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$updates = $bot->getUpdates();
foreach ($updates as $update) {
    var_dump($update->getMessage()->getText());
    var_dump($update->getChat()->getId());

    $bot->sendMessage(['chat_id' => $update->getChat()->getId(), 'text' => 'something']);
}

var_dump('finish');
