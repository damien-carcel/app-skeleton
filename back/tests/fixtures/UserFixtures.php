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

namespace App\Tests\Fixtures;

use App\Domain\Model\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserFixtures extends Fixture
{
    public const NORMALIZED_USERS = [
        '02432f0b-c33e-4d71-8ba9-a5e3267a45d5' => [
            'firstName' => 'Tony',
            'lastName' => 'Stark',
            'username' => 'ironman',
            'password' => 'password',
            'salt' => 'salt',
            'roles' => [],
        ],
        '7f57d041-a612-4a5a-a61a-e0c96b2c576e' => [
            'firstName' => 'Steve',
            'lastName' => 'Rogers',
            'username' => 'captain',
            'password' => 'password',
            'salt' => 'salt',
            'roles' => [],
        ],
        'fff8bb6d-5772-4e6c-9d10-41d522683264' => [
            'firstName' => 'Bruce',
            'lastName' => 'Banner',
            'username' => 'hulk',
            'password' => 'password',
            'salt' => 'salt',
            'roles' => [],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $objectManager): void
    {
        foreach (static::NORMALIZED_USERS as $userId => $normalizedUser) {
            $user = new User(
                Uuid::fromString($userId),
                $normalizedUser['username'],
                $normalizedUser['firstName'],
                $normalizedUser['lastName'],
                $normalizedUser['password'],
                $normalizedUser['salt'],
                $normalizedUser['roles']
            );

            $objectManager->persist($user);
        }

        $objectManager->flush();
    }
}
