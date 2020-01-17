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
use Carcel\User\Domain\Exception\UserDoesNotExist;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/api/users/{uuid}", name="rest_users_get", methods={"GET"})
 */
final class GetController
{
    public function __invoke(string $uuid, GetUserHandler $handler): Response
    {
        $getUser = new GetUser();
        $getUser->identifier = $uuid;

        try {
            $user = ($handler)($getUser);
        } catch (UserDoesNotExist $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        }

        return new JsonResponse($user->normalize());
    }
}
