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

namespace Carcel\Tests\Acceptance\Context;

use Behat\Behat\Context\Context;
use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Factory\UserFactory;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Carcel\User\Domain\Service\EncodePassword;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class FixtureContext implements Context
{
    private UserFactory $factory;
    private UserRepositoryInterface $repository;
    private EncodePassword $encodePassword;

    public function __construct(
        UserFactory $factory,
        UserRepositoryInterface $repository,
        EncodePassword $encodePassword
    ) {
        $this->factory = $factory;
        $this->repository = $repository;
        $this->encodePassword = $encodePassword;
    }

    /**
     * @BeforeScenario
     */
    public function loadUsers(): void
    {
        $userIds = array_keys(UserFixtures::USERS_DATA);

        foreach ($userIds as $id) {
            $user = $this->factory->create(
                $id,
                UserFixtures::USERS_DATA[$id]['firstName'],
                UserFixtures::USERS_DATA[$id]['lastName'],
                UserFixtures::USERS_DATA[$id]['email'],
                ($this->encodePassword)(UserFixtures::USERS_DATA[$id]['password']),
            );

            $this->repository->create($user);
        }
    }
}
