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

namespace App\Tests\Acceptance\Context;

use App\Domain\Repository\UserRepositoryInterface;
use App\Tests\Fixtures\UserFixtures;
use Behat\Behat\Context\Context;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class FixtureContext implements Context
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @BeforeScenario
     */
    public function loadUsers(): void
    {
        $users = UserFixtures::instantiateUserEntities();

        foreach ($users as $user) {
            $this->userRepository->save($user);
        }
    }
}
