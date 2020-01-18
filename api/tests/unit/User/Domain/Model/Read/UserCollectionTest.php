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

namespace Carcel\Tests\Unit\User\Domain\Model\Read;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Model\Read\UserCollection;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserCollectionTest extends TestCase
{
    private array $usersData;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->usersData = UserFixtures::getNormalizedUsers();
    }

    /** @test */
    public function itCanCreateAnEmptyUserCollection(): void
    {
        static::assertInstanceOf(UserCollection::class, new UserCollection([]));
    }

    /** @test */
    public function aUserCollectionCanNormalizeItself(): void
    {
        $userCollection = $this->instantiateUserCollection();

        static::assertSame($this->usersData, $userCollection->normalize());
    }

    private function instantiateUserCollection(): UserCollection
    {
        return new UserCollection($this->usersData);
    }
}
