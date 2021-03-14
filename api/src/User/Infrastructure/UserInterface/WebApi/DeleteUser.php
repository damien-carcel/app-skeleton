<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2021 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Infrastructure\UserInterface\WebApi;

use Carcel\User\Application\Command\DeleteUser as DeleteUserCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class DeleteUser
{
    private MessageBusInterface $bus;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        MessageBusInterface $bus,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->bus = $bus;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function __invoke(string $id): JsonResponse
    {
        $command = new DeleteUserCommand($id);

        $violations = $this->validator->validate($command);
        if (0 < $violations->count()) {
            return new JsonResponse($this->serializer->serialize($violations, JsonEncoder::FORMAT), 400);
        }

        $this->bus->dispatch($command);

        return new JsonResponse(null, 202);
    }
}
