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

use Carcel\User\Application\Query\GetUser as GetUserQuery;
use Carcel\User\Domain\Model\Read\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUser
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
        $query = new GetUserQuery($id);

        $violations = $this->validator->validate($query);
        if (0 < $violations->count()) {
            return new JsonResponse(
                $this->serializer->serialize($violations, JsonEncoder::FORMAT),
                Response::HTTP_NOT_FOUND
            );
        }

        $envelope = $this->bus->dispatch($query);
        /** @var ?HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        if (null === $handledStamp) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        /** @var User $user */
        $user = $handledStamp->getResult();

        return new JsonResponse($user->normalize(), Response::HTTP_OK);
    }
}
