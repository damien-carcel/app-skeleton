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

namespace App\Tests\Integration\Context;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Tests\Fixtures\UserFixtures;
use Behat\Behat\Context\Context;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class FeatureContext implements Context
{
    /** @var UserRepositoryInterface */
    private $doctrineUserRepository;

    /** @var array */
    private $result;

    /**
     * @param UserRepositoryInterface $doctrineUserRepository
     */
    public function __construct(UserRepositoryInterface $doctrineUserRepository)
    {
        $this->doctrineUserRepository = $doctrineUserRepository;
    }

    /**
     * @param string $methodName
     *
     * @When the ":methodName" method from the Doctrine UserRepository is called
     */
    public function callGetMethodFromRepository(string $methodName): void
    {
        $this->result = $this->doctrineUserRepository->$methodName();
    }

    /**
     * @Then all the users should be retrieved from database
     */
    public function allUsersAreRetrieved(): void
    {
        Assert::count($this->result, 3);

        $normalizedUsers = [];
        foreach ($this->result as $user) {
            Assert::isInstanceOf($user, User::class);
            Assert::isInstanceOf($user->id(), Uuid::class);

            $normalizedUsers[] = [
                'title' => $user->title(),
                'content' => $user->content(),
            ];
        }

        Assert::allOneOf($normalizedUsers, UserFixtures::NORMALIZED_USERS);
    }
}
