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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users", name="rest_users_create", methods={"POST"})
 */
final class CreateController
{
    public function __invoke(Request $request, MessageBusInterface $bus): Response
    {
        $content = $request->getContent();
        $userData = json_decode($content, true);

        try {
            $createUser = new CreateUser(
                $userData['email'],
                $userData['firstName'],
                $userData['lastName']
            );
            $bus->dispatch($createUser);
        } catch (HandlerFailedException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        return new JsonResponse();
    }
}
