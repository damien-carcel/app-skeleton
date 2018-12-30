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

use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users/{uuid}", name="rest_users_get", methods={"GET"})
 */
final class GetController
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
     * @param string $uuid
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function __invoke(string $uuid): Response
    {
        $user = $this->repository->find($uuid);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf(
                'There is no user with identifier "%s"',
                $uuid
            ));
        }

        $normalizedUser = [
            'id' => $user->id(),
            'username' => $user->getUsername(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
        ];

        return new JsonResponse($normalizedUser);
    }
}
