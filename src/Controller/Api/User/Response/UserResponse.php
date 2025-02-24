<?php

declare(strict_types=1);

namespace App\Controller\Api\User\Response;

use App\Infrastructure\Doctrine\Entity\User;

final readonly class UserResponse
{
    static public function createUserResponse(User $user): array
    {
        return [
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'role' => $user->getRole(),
            'name' => $user->getName(),
            'updatedAt' => $user->getUpdatedAt(),
        ];
    }

    static public function createUsersResponse(array $users): array
    {
        $result = [];
        foreach ($users as $user) {
            $result[] = UserResponse::createUserResponse($user);
        }

        return $result;
    }
}
