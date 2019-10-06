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

use Carcel\User\Application\Query\GetUser;
use Carcel\User\Application\Query\GetUserHandler;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users/{uuid}", name="rest_users_get", methods={"GET"})
 */
final class GetController
{
    private $getUserHandler;

    public function __construct(GetUserHandler $getUserHandler)
    {
        $this->getUserHandler = $getUserHandler;
    }

    public function __invoke(string $uuid): Response
    {
        $user = ($this->getUserHandler)(new GetUser(Uuid::fromString($uuid)));

        if (null === $user) {
            throw new NotFoundHttpException(sprintf(
                'There is no user with identifier "%s"',
                $uuid
            ));
        }

        return new JsonResponse($user->normalize());
    }
}
