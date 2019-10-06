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

use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users/{uuid}", name="rest_users_update", methods={"PATCH"})
 */
final class UpdateController
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $uuid, Request $request): Response
    {
        $content = $request->getContent();
        $userData = json_decode($content, true);

        $user = $this->repository->find($uuid);
        if (null === $user) {
            throw new NotFoundHttpException(sprintf(
                'There is no user with identifier "%s"',
                $uuid
            ));
        }
        $user->changeName($userData);

        $this->repository->save($user);

        return new JsonResponse();
    }
}
