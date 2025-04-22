<?php

declare(strict_types=1);

namespace App\Controller\Api\Telegram\Marriage\Message\Request;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class SendMessageRequest
{
    public function __construct(
        #[Serializer\Type(name: 'string')]
        #[Assert\NotBlank]
        public string $type,
    ) {
    }
}
