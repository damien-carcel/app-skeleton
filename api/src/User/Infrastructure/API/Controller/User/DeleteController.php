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

use Carcel\User\Application\Command\DeleteUser;
use Carcel\User\Domain\Exception\UserDoesNotExist;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/api/users/{uuid}", name="rest_users_delete", methods={"DELETE"})
 */
final class DeleteController
{
    public function __invoke(string $uuid, MessageBusInterface $bus): Response
    {
        $deleteUser = new DeleteUser();
        $deleteUser->identifier = $uuid;

        try {
            $bus->dispatch($deleteUser);
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
