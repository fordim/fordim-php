<?php

declare(strict_types=1);

namespace App\Controller\Api\User\Request;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

final class AddUserRequest
{
    public function __construct(
        #[Serializer\Type(name: 'string')]
        #[Assert\NotBlank]
        public readonly string $login,
        #[Serializer\Type(name: 'string')]
        #[Assert\NotBlank]
        public readonly string $password,
        #[Serializer\Type(name: 'string')]
        #[Assert\NotBlank]
        public readonly string $role,
        #[Serializer\Type(name: 'string')]
        #[Assert\NotBlank]
        public readonly string $name,
    ) {
    }
}
