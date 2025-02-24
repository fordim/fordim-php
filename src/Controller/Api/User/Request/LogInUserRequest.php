<?php

declare(strict_types=1);

namespace App\Controller\Api\User\Request;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class LogInUserRequest
{
    public function __construct(
        #[Serializer\Type(name: 'string')]
        #[Assert\NotBlank]
        public string $login,
        #[Serializer\Type(name: 'string')]
        #[Assert\NotBlank]
        public string $password,
    ) {
    }
}
