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
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users", name="rest_users_create", methods={"POST"})
 */
final class CreateController
{
    private $bus;
    private $validator;

    public function __construct(MessageBusInterface $bus, ValidatorInterface $validator)
    {
        $this->bus = $bus;
        $this->validator = $validator;
    }

    public function __invoke(Request $request): Response
    {
        $content = $request->getContent();
        $userData = json_decode($content, true);

        $this->dispatchMessage($this->createUserMessage(
            $userData['firstName'],
            $userData['lastName'],
            $userData['email'],
        ));

        return new JsonResponse();
    }

    private function dispatchMessage(CreateUser $createUser): void
    {
        try {
            $this->bus->dispatch($createUser);
        } catch (HandlerFailedException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }
    }

    private function createUserMessage(string $firstName, string $lastName, string $email): CreateUser
    {
        $createUser = new CreateUser(
            $firstName,
            $lastName,
            $email,
        );

        $violations = $this->validator->validate($createUser);
        if (count($violations) > 0) {
            throw new UnprocessableEntityHttpException((string) $violations);
        }

        return $createUser;
    }
}
