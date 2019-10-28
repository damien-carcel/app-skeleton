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

use Carcel\User\Application\Command\UpdateUserData;
use Carcel\User\Application\Command\UpdateUserDataHandler;
use Carcel\User\Domain\Exception\UserDoesNotExist;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users/{uuid}", name="rest_users_update", methods={"PATCH"})
 */
final class UpdateController
{
    private $changeUserNameHandler;

    public function __construct(UpdateUserDataHandler $changeUserNameHandler)
    {
        $this->changeUserNameHandler = $changeUserNameHandler;
    }

    public function __invoke(string $uuid, Request $request): Response
    {
        $content = $request->getContent();
        $userData = json_decode($content, true);

        try {
            $changeUserName = new UpdateUserData(
                Uuid::fromString($uuid),
                $userData['email'],
                $userData['firstName'],
                $userData['lastName']
            );
            ($this->changeUserNameHandler)($changeUserName);
        } catch (UserDoesNotExist $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        }

        return new JsonResponse();
    }
}
