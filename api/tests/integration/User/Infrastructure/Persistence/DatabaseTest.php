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

namespace Carcel\Tests\Integration\User\Infrastructure\Persistence;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\Tests\Integration\TestCase;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class DatabaseTest extends TestCase
{
    private const TONY_STARK_ID = '02432f0b-c33e-4d71-8ba9-a5e3267a45d5';

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadUserFixtures([self::TONY_STARK_ID]);
    }

    /** @test */
    public function emailUniquenessIsEnsuredByTheDatabase(): void
    {
        self::expectException(UniqueConstraintViolationException::class);

        $this->container()->get('doctrine.dbal.default_connection')->insert(
            'user',
            [
                'id' => Uuid::uuid4()->toString(),
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => UserFixtures::USERS_DATA[self::TONY_STARK_ID]['email'],
                'password' => 'password',
            ],
        );
    }
}
