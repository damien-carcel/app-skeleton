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

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Tests\Fixtures\UserFixtures;
use Behat\Behat\Context\Context;
use Ramsey\Uuid\Uuid;

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
    public function loadFixturesWithOnlyUsers(): void
    {
        $normalizedUsers = UserFixtures::NORMALIZED_USERS;

        foreach ($normalizedUsers as $normalizedUser) {
            $user = new User(Uuid::uuid4(), $normalizedUser['title'], $normalizedUser['content']);

            $this->userRepository->save($user);
        }
    }
}
