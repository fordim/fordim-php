<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Domain\Telegram\Command\TelegramUser\AddAndUpdateUserCommand;
use App\Domain\Telegram\Type\TelegramType;
use Telegram\Bot\Commands\Command;

final class WeddingHallCommand extends Command
{
    protected string $name = 'wedding_hall';
    protected string $description = 'Место проведение церимонии бракосочетания';

    private const YANDEX_LINK = 'https://yandex.ru/maps/-/CHVLqGkT';

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
                ⛪️ Брачная церимония состоятся по адресу:
                г. Краснодар, ул. Офицерская 47. <a href="%s">(ссылка)</a>
                Всех гостей ждем у основного входа в главный Екатерининский зал.
                TXT,
                self::YANDEX_LINK,
            ),
        ]);
    }
}
