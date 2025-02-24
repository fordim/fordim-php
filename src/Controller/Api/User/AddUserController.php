<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Controller\Api\User\Request\AddUserRequest;
use App\Infrastructure\Doctrine\Entity\User;
use App\Infrastructure\Repository\Doctrine\User\UserRepository;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

final readonly class AddUserController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route(path: '/api/user/add', methods: ['POST'])]
    #[ParamConverter('request', converter: 'fos_rest.request_body')]
    public function __invoke(AddUserRequest $request): View
    {
        //TODO ifExist

        $newUser = new User();
        $newUser->setLogin($request->login);
        $newUser->setPassword($request->password);
        $newUser->setRole($request->role);
        $newUser->setName($request->name);
        $newUser->setCreatedAt(new \DateTimeImmutable());
        $newUser->setUpdatedAt(new \DateTimeImmutable());

        $this->userRepository->save($newUser);

        return View::create(null, Response::HTTP_NO_CONTENT);
    }

}
