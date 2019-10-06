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
use Carcel\User\Application\Query\GetUserListHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users", name="rest_users_list", methods={"GET"})
 */
final class ListController
{
    private $getUserListHandler;

    public function __construct(GetUserListHandler $getUserListHandler)
    {
        $this->getUserListHandler = $getUserListHandler;
    }

    public function __invoke(Request $request): Response
    {
        $limit = null === $request->query->get('limit') ? 10 : (int) $request->query->get('_limit');
        $page = null === $request->query->get('_page') ? 1 : (int) $request->query->get('_page');

        $userList = ($this->getUserListHandler)(new GetUserList($limit, $page));

        return new JsonResponse($userList->normalize());
    }
}
