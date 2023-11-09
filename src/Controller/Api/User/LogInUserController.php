<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\Api\User\Request\LogInUserRequest;
use App\Controller\Api\User\Response\UserResponse;
use App\Infrastructure\Repository\Doctrine\User\UserRepository;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class LogInUserController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route(path: '/api/user/login', methods: ['POST'])]
    #[ParamConverter('request', converter: 'fos_rest.request_body')]
    public function __invoke(LogInUserRequest $request): View
    {
        $user = $this->userRepository->findOneBy(['login' => $request->login]);
        if ($user === null) {
            return View::create('User not found',Response::HTTP_NOT_FOUND);
        }

        if ($user->getPassword() !== $request->password) {
            return View::create('Wrong password',Response::HTTP_BAD_REQUEST);
        }

        return View::create(UserResponse::createUserResponse($user));
    }

}
