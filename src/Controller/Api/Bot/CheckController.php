<?php

declare(strict_types=1);

namespace App\Controller\Api\Bot;

use App\Infrastructure\Repository\Doctrine\User\UserRepository;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Telegram\Bot\Api;

class CheckController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route(path: '/api/bot', methods: ['GET'])]
    public function __invoke(): View
    {
        $bot = new Api('7763662258:AAG72ZYqY3ljWwPl4Kp8ydSqLw-pOf09z6I');
        $myChatId = 576623234;

        $response = $bot->getMe();
        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();

//        var_dump($botId, $firstName, $username);

        $updates = $bot->getUpdates();
        foreach ($updates as $update) {
            var_dump($update->getMessage()->getText());
            var_dump($update->getChat()->getId());
        }

        var_dump('finish');
//        $bot->sendMessage(['chat_id' => $myChatId, 'text' => 'Как дела?']);

        return View::create(['data' => 'something']);
    }
}
