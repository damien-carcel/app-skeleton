<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Infrastructure\API\Controller\User;

use Carcel\User\Application\Command\CreateUser;
use Carcel\User\Application\Command\CreateUserHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users", name="rest_users_create", methods={"POST"})
 */
final class CreateController
{
    private $createUserHandler;

    public function __construct(CreateUserHandler $createUserHandler)
    {
        $this->createUserHandler = $createUserHandler;
    }

    public function __invoke(Request $request): Response
    {
        $content = $request->getContent();
        $userData = json_decode($content, true);

        $createUser = new CreateUser(
            $userData['email'],
            $userData['firstName'],
            $userData['lastName']
        );
        ($this->createUserHandler)($createUser);

        return new JsonResponse();
    }
}
