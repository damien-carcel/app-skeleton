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

use Carcel\User\Domain\Exception\EmailIsAlreadyUsed;
use Carcel\User\Domain\Factory\UserFactory;
use Carcel\User\Domain\QueryFunction\IsEmailAlreadyUsed;
use Carcel\User\Domain\Repository\UserRepository;
use Carcel\User\Domain\Service\EncodePassword;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class CreateUserHandler implements MessageHandlerInterface
{
    private EncodePassword $encodePassword;
    private IsEmailAlreadyUsed $isEmailAlreadyUsed;
    private UserFactory $userFactory;
    private UserRepository $userRepository;

    public function __construct(
        EncodePassword $encodePassword,
        IsEmailAlreadyUsed $isEmailAlreadyUsed,
        UserFactory $userFactory,
        UserRepository $userRepository
    ) {
        $this->encodePassword = $encodePassword;
        $this->isEmailAlreadyUsed = $isEmailAlreadyUsed;
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    public function __invoke(CreateUser $createUser): void
    {
        if (($this->isEmailAlreadyUsed)($createUser->email)) {
            throw EmailIsAlreadyUsed::fromEmail($createUser->email);
        }

        $user = $this->userFactory->create(
            (Uuid::uuid4())->toString(),
            $createUser->firstName,
            $createUser->lastName,
            $createUser->email,
            ($this->encodePassword)($createUser->password),
        );

        $this->userRepository->create($user);
    }
}
