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

use Carcel\User\Domain\Exception\UserDoesNotExist;
use Carcel\User\Domain\Model\Write\Email;
use Carcel\User\Domain\Repository\UserRepositoryInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UpdateUserDataHandler
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UpdateUserData $changeUserName): void
    {
        $user = $this->userRepository->find($changeUserName->identifier()->toString());
        if (null === $user) {
            throw UserDoesNotExist::fromUuid($changeUserName->identifier());
        }

        $email = Email::fromString($changeUserName->email());

        $user->update($email, $changeUserName->firstName(), $changeUserName->lastName());

        $this->userRepository->save($user);
    }
}
