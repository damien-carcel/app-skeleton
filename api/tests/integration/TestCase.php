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

namespace Carcel\Tests\Integration;

use Carcel\Tests\Fixtures\UserFixtures;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class TestCase extends KernelTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        static::bootKernel();

        $this->container()->get('database_connection')->executeUpdate('DELETE FROM user');
    }

    /**
     * Using this special test container allows to get private services,
     * but only if they are already injected somewhere in the application.
     */
    protected function container(): ContainerInterface
    {
        return static::$container;
    }

    /**
     * @param string[] $usersIdsToLoad
     */
    protected function loadUserFixtures(array $usersIdsToLoad = []): void
    {
        if (empty($usersIdsToLoad)) {
            $usersIdsToLoad = array_keys(UserFixtures::USERS_DATA);
        }

        $connection = $this->container()->get('database_connection');
        foreach ($usersIdsToLoad as $id) {
            $connection->insert('user', [
                'id' => $id,
                'first_name' => UserFixtures::USERS_DATA[$id]['firstName'],
                'last_name' => UserFixtures::USERS_DATA[$id]['lastName'],
                'email' => UserFixtures::USERS_DATA[$id]['email'],
                'password' => UserFixtures::USERS_DATA[$id]['password'],
            ]);
        }
    }
}
