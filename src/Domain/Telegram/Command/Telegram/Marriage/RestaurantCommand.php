<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Commands\Command;

final class RestaurantCommand extends Command
{
    protected string $name = 'restaurant';
    protected string $description = 'Место проведение банкета';

    private const YANDEX_LINK = 'https://yandex.ru/maps/-/CHVLuZ1j';

    public function __construct(
        private readonly AddAndUpdateUserCommand $addAndUpdateUserCommand,
        private readonly AddTextLog $addTextLog,
    ) {
    }

    public function handle(): void
    {
        $message = $this->getUpdate()->getMessage();
        $from = $this->getUpdate()->getMessage()->getFrom();

        $telegramUser = $this->addAndUpdateUserCommand->process($from, TelegramType::wedding);
        $this->addTextLog->process($telegramUser, $message);

        $this->replyWithMessage([
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
            'text' => sprintf(
                <<<'TXT'
                🍽️ Собираемся в ресторане по адресу:
                г. Краснодар, ул. Чапаева 86. <a href="%s?utm_source=telegram">(ссылка)</a>
                TXT,
                self::YANDEX_LINK,
            ),
        ]);
    }
}
