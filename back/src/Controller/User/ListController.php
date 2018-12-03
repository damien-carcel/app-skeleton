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

namespace App\Controller\User;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
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
    /** @var UserRepositoryInterface */
    private $repository;

    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        $users = $this->repository->findAll();

        $normalizedUsers = array_map(function (User $user) {
            return [
                'id' => $user->id(),
                'title' => $user->title(),
                'content' => $user->content(),
            ];
        }, $users);

        return new JsonResponse($normalizedUsers);
    }
}
