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
use Carcel\User\Domain\Exception\UserDoesNotExist;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users/{uuid}", name="rest_users_update", methods={"PATCH"})
 */
final class UpdateController
{
    public function __invoke(string $uuid, Request $request, MessageBusInterface $bus): Response
    {
        $content = $request->getContent();
        $userData = json_decode($content, true);

        try {
            $changeUserName = new UpdateUserData(
                $uuid,
                $userData['email'],
                $userData['firstName'],
                $userData['lastName']
            );
            $bus->dispatch($changeUserName);
        } catch (HandlerFailedException $exception) {
            $handledExceptions = $exception->getNestedExceptions();

            if (current($handledExceptions) instanceof UserDoesNotExist) {
                throw new NotFoundHttpException($exception->getMessage(), $exception);
            }

            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        return new JsonResponse();
    }
}
