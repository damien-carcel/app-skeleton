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
use Carcel\User\Domain\Model\Write\FirstName;
use Carcel\User\Domain\Model\Write\LastName;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UpdateUserDataHandler implements MessageHandlerInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UpdateUserData $changeUserName): void
    {
        $user = $this->userRepository->find(Uuid::fromString($changeUserName->identifier()));
        if (null === $user) {
            throw UserDoesNotExist::fromUuid($changeUserName->identifier());
        }

        $email = Email::fromString($changeUserName->email());
        $firstName = FirstName::fromString($changeUserName->firstName());
        $lastName = LastName::fromString($changeUserName->lastName());

        $user->update($email, $firstName, $lastName);

        $this->userRepository->save($user);
    }
}
