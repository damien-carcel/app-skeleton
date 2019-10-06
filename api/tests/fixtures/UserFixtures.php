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

namespace Carcel\Tests\Fixtures;

use Carcel\User\Domain\Factory\UserFactory;
use Carcel\User\Domain\Model\Write\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserFixtures extends Fixture
{
    public const USERS_DATA = [
        '02432f0b-c33e-4d71-8ba9-a5e3267a45d5' => [
            'firstName' => 'Tony',
            'lastName' => 'Stark',
            'username' => 'ironman',
            'password' => 'password',
            'salt' => 'salt',
            'roles' => [],
        ],
        '08acf31d-2e62-44e9-ba18-fd160ac125ad' => [
            'firstName' => 'Wanda Marya',
            'lastName' => 'Maximoff',
            'username' => 'scarlet_witch',
            'password' => 'password',
            'salt' => 'salt',
            'roles' => [],
        ],
        '1605a575-77e5-4427-bbdb-2ebcb8cc8033' => [
            'firstName' => 'Peter',
            'lastName' => 'Parker',
            'username' => 'spider-man',
            'password' => 'password',
            'salt' => 'salt',
            'roles' => [],
        ],
        '22cd05c9-622d-4dcb-8837-1975e8c08812' => [
            'firstName' => 'Natasha',
            'lastName' => 'Romanoff',
            'username' => 'black_widow',
            'password' => 'password',
            'salt' => 'salt',
            'roles' => [],
        ],
        '2a2a63c2-f01a-4b28-b52b-922bd6a170f5' => [
            'firstName' => 'T\'Challa',
            'lastName' => 'King of Wakanda',
            'username' => 'black_panther',
            'password' => 'password',
            'salt' => 'salt',
            'roles' => [],
        ],
        '3553b4cf-49ab-4dd6-ba6e-e09b5b96115c' => [
            'firstName' => 'Victor',
            'lastName' => 'Shade',
            'username' => 'vision',
            'password' => 'password',
            'salt' => 'salt',
            'roles' => [],
        ],
        '5eefa64f-0800-4fe2-b86f-f3d96bf7d602' => [
            'firstName' => 'Clint',
            'lastName' => 'Barton',
            'username' => 'hawkeye',
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
        '9f9e9cd2-88bb-438f-b825-b9610c6ee3f4' => [
            'firstName' => 'Loki',
            'lastName' => 'Laufeyson',
            'username' => 'loki',
            'password' => 'password',
            'salt' => 'salt',
            'roles' => [],
        ],
        'd24b8b4a-2476-48f7-b865-ee5318d845f3' => [
            'firstName' => 'Thor',
            'lastName' => 'Odinson',
            'username' => 'thor',
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

    private $userIdsToLoad;

    public function __construct(array $userIdsToLoad = [])
    {
        $this->userIdsToLoad = $userIdsToLoad;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $objectManager): void
    {
        if (empty($this->userIdsToLoad)) {
            $users = static::instantiateUserEntities();
        } else {
            $users = array_map(function (string $userId) {
                return $this->instantiateUserEntity($userId);
            }, $this->userIdsToLoad);
        }

        foreach ($users as $user) {
            $objectManager->persist($user);
        }

        $objectManager->flush();
    }

    /**
     * @return User[]
     */
    public static function instantiateUserEntities(): array
    {
        $users = [];
        foreach (static::USERS_DATA as $userId => $userData) {
            $users[] = static::createUser(array_merge(
                ['id' => $userId],
                $userData
            ));
        }

        return $users;
    }

    public static function instantiateUserEntity(string $userId): User
    {
        return static::createUser(array_merge(
            ['id' => $userId],
            static::USERS_DATA[$userId]
        ));
    }

    public static function getNormalizedUsers(): array
    {
        $normalizedUsers = [];
        foreach (UserFixtures::USERS_DATA as $userId => $userData) {
            $normalizedUsers[] = static::getNormalizedUser($userId);
        }

        return $normalizedUsers;
    }

    public static function getNormalizedUser(string $userId): array
    {
        return [
            'id' => $userId,
            'username' => static::USERS_DATA[$userId]['username'],
            'firstName' => static::USERS_DATA[$userId]['firstName'],
            'lastName' => static::USERS_DATA[$userId]['lastName'],
        ];
    }

    private static function createUser(array $userData): User
    {
        return (new UserFactory())->create($userData);
    }
}
