<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2020 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\Tests\Unit\User\Infrastructure\Persistence\InMemory\QueryFunction;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Factory\UserFactory;
use Carcel\User\Domain\Repository\UserRepository;
use Carcel\User\Infrastructure\Persistence\InMemory\QueryFunction\IsEmailAlreadyUsedInMemory;
use Carcel\User\Infrastructure\Persistence\InMemory\Repository\InMemoryUserRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class IsEmailAlreadyUsedInMemoryTest extends TestCase
{
    private const TONY_STARK_ID = '02432f0b-c33e-4d71-8ba9-a5e3267a45d5';

    private IsEmailAlreadyUsedInMemory $isEmailAlreadyUsed;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->isEmailAlreadyUsed = new IsEmailAlreadyUsedInMemory($this->userRepository());
    }

    /** @test */
    public function itAnswersIfTheEmailIsAlreadyInUsed(): void
    {
        Assert::assertTrue(($this->isEmailAlreadyUsed)(UserFixtures::USERS_DATA[self::TONY_STARK_ID]['email']));
    }

    /** @test */
    public function itAnswersIfTheEmailIsNotAlreadyInUsed(): void
    {
        Assert::assertFalse(($this->isEmailAlreadyUsed)('batman@justiceligue.org'));
    }

    private function userRepository(): UserRepository
    {
        $factory = new UserFactory();
        $repository = new InMemoryUserRepository();

        $userIds = array_keys(UserFixtures::USERS_DATA);

        foreach ($userIds as $id) {
            $user = $factory->create(
                $id,
                UserFixtures::USERS_DATA[$id]['firstName'],
                UserFixtures::USERS_DATA[$id]['lastName'],
                UserFixtures::USERS_DATA[$id]['email'],
                UserFixtures::USERS_DATA[$id]['password'],
            );

            $repository->create($user);
        }

        return $repository;
    }
}
