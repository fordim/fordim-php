<?php

declare(strict_types=1);

namespace App\Controller\Api\Telegram;

use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\User;

final readonly class CheckController
{
    public function __construct(
        private AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private AddTextLog $addTextLog,
    ) {
    }

    /**
     * @throws TelegramSDKException
     */
    #[Route(path: '/api/wedding-bot', methods: ['GET'])]
    public function __invoke(): View
    {
        $bot = new Api('7763662258:AAG72ZYqY3ljWwPl4Kp8ydSqLw-pOf09z6I');

        $updates = $bot->getUpdates();

        /** @var User $from */
        $from = $updates[0]->getMessage()->getFrom();
        $message = $updates[1]->getMessage();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::fordim);
        $this->addTextLog->process($telegramUser, $message);

        dd(101);

//        var_dump('FINISH');
//        $bot->sendMessage(['chat_id' => $myChatId, 'text' => 'Как дела?']);

        return View::create(['data' => 'something']);
    }
}
