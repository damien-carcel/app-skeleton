<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\API\Controller\User;

use App\Application\Query\GetUserList;
use App\Application\Query\GetUserListHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users", name="rest_users_list", methods={"GET"})
 */
final class ListController
{
    /** @var GetUserListHandler */
    private $getUserListHandler;

    /**
     * @param GetUserListHandler $getUserListHandler
     */
    public function __construct(GetUserListHandler $getUserListHandler)
    {
        $this->getUserListHandler = $getUserListHandler;
    }

    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        $userList = $this->getUserListHandler->handle(new GetUserList(10, 1));

        return new JsonResponse($userList->normalize());
    }
}
