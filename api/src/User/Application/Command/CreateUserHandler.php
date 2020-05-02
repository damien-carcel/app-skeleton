<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2019 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Application\Command;

use Carcel\User\Domain\Factory\UserFactory;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class CreateUserHandler implements MessageHandlerInterface
{
    private UserFactory $userFactory;
    private UserRepositoryInterface $userRepository;

    public function __construct(UserFactory $userFactory, UserRepositoryInterface $userRepository)
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    public function __invoke(CreateUser $createUser): void
    {
        $user = $this->userFactory->create(
            (Uuid::uuid4())->toString(),
            $createUser->firstName,
            $createUser->lastName,
            $createUser->email,
            $createUser->password,
        );

        $this->userRepository->create($user);
    }
}
