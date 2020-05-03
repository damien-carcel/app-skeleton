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

namespace Carcel\Tests\EndToEnd\Context;

use Behat\Behat\Context\Context;
use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Service\EncodePassword;
use Doctrine\DBAL\Connection;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class FixtureContext implements Context
{
    private Connection $connection;
    private EncodePassword $encodePassword;

    public function __construct(Connection $connection, EncodePassword $encodePassword)
    {
        $this->connection = $connection;
        $this->encodePassword = $encodePassword;
    }

    /**
     * @BeforeScenario
     */
    public function loadUsers(): void
    {
        $this->connection->executeUpdate('DELETE FROM user');

        foreach (UserFixtures::USERS_DATA as $id => $data) {
            $this->connection->insert('user', [
                'id' => $id,
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'email' => $data['email'],
                'password' => ($this->encodePassword)($data['password']),
            ]);
        }
    }
}
