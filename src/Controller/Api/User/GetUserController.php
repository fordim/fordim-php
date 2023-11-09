<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\Api\User\Response\UserResponse;
use App\Infrastructure\Repository\Doctrine\User\UserRepository;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class GetUserController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route(path: '/api/user/{id}', methods: ['GET'])]
    public function __invoke(int $id): View
    {
        $user = $this->userRepository->find($id);
        if ($user === null) {
            return View::create(null,Response::HTTP_NOT_FOUND);
        }

        return View::create(UserResponse::createUserResponse($user));
    }
}
