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

use Carcel\User\Application\Query\GetUserList;
use Carcel\User\Domain\Model\Read\UserList;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/api/users", name="rest_users_list", methods={"GET"})
 */
final class ListController
{
    public function __invoke(Request $request, MessageBusInterface $bus): Response
    {
        $limit = null === $request->query->get('limit') ? 10 : (int) $request->query->get('_limit');
        $page = null === $request->query->get('_page') ? 1 : (int) $request->query->get('_page');

        try {
            $envelope = $bus->dispatch(new GetUserList($limit, $page));
        } catch (HandlerFailedException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        return new JsonResponse($this->getQueriedUserList($envelope)->normalize());
    }

    private function getQueriedUserList(Envelope $envelope): UserList
    {
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}
