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

namespace Carcel\User\Infrastructure\API\Controller\User;

use Carcel\User\Domain\Factory\UserFactory;
use Carcel\User\Domain\Model\Write\User;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 *
 * @Route("/users", name="rest_users_create", methods={"POST"})
 */
final class CreateController
{
    /** @var UserFactory */
    private $userFactory;

    /** @var UserRepositoryInterface */
    private $repository;

    /**
     * @param UserFactory             $userFactory
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserFactory $userFactory, UserRepositoryInterface $repository)
    {
        $this->userFactory = $userFactory;
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $content = $request->getContent();
        $userData = json_decode($content, true);

        $user = $this->userFactory->create(array_merge(
            [
                'password' => 'password',
                'salt' => 'salt',
                'roles' => [],
            ],
            $userData
        ));

        $this->repository->save($user);

        return new JsonResponse();
    }
}
