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

use Carcel\User\Domain\Model\Write\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserFixtures extends Fixture
{
    public const USERS_DATA = [
        '02432f0b-c33e-4d71-8ba9-a5e3267a45d5' => [
            'email' => 'ironman@avengers.org',
            'firstName' => 'Tony',
            'lastName' => 'Stark',
        ],
        '08acf31d-2e62-44e9-ba18-fd160ac125ad' => [
            'email' => 'scarlet.witch@avengers.org',
            'firstName' => 'Wanda Marya',
            'lastName' => 'Maximoff',
        ],
        '1605a575-77e5-4427-bbdb-2ebcb8cc8033' => [
            'email' => 'spider.man@avengers.org',
            'firstName' => 'Peter',
            'lastName' => 'Parker',
        ],
        '22cd05c9-622d-4dcb-8837-1975e8c08812' => [
            'email' => 'black.widow@avengers.org',
            'firstName' => 'Natasha',
            'lastName' => 'Romanoff',
        ],
        '2a2a63c2-f01a-4b28-b52b-922bd6a170f5' => [
            'email' => 'black.panther@avengers.org',
            'firstName' => 'T\'Challa',
            'lastName' => 'King of Wakanda',
        ],
        '3553b4cf-49ab-4dd6-ba6e-e09b5b96115c' => [
            'email' => 'vision@avengers.org',
            'firstName' => 'Victor',
            'lastName' => 'Shade',
        ],
        '5eefa64f-0800-4fe2-b86f-f3d96bf7d602' => [
            'email' => 'hawkeye@avengers.org',
            'firstName' => 'Clint',
            'lastName' => 'Barton',
        ],
        '7f57d041-a612-4a5a-a61a-e0c96b2c576e' => [
            'email' => 'captain@avengers.org',
            'firstName' => 'Steve',
            'lastName' => 'Rogers',
        ],
        '9f9e9cd2-88bb-438f-b825-b9610c6ee3f4' => [
            'email' => 'loki@avengers.org',
            'firstName' => 'Loki',
            'lastName' => 'Laufeyson',
        ],
        'd24b8b4a-2476-48f7-b865-ee5318d845f3' => [
            'email' => 'thor@avengers.org',
            'firstName' => 'Thor',
            'lastName' => 'Odinson',
        ],
        'fff8bb6d-5772-4e6c-9d10-41d522683264' => [
            'email' => 'hulk@avengers.org',
            'firstName' => 'Bruce',
            'lastName' => 'Banner',
        ],
    ];

    public const ID_OF_NON_EXISTENT_USER = 'eba840cf-9317-4735-b03b-6facfa279890';

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
            'email' => static::USERS_DATA[$userId]['email'],
            'firstName' => static::USERS_DATA[$userId]['firstName'],
            'lastName' => static::USERS_DATA[$userId]['lastName'],
        ];
    }

    private static function createUser(array $userData): User
    {
        return new User(
            Uuid::fromString($userData['id']),
            $userData['email'],
            $userData['firstName'],
            $userData['lastName'],
        );
    }
}
