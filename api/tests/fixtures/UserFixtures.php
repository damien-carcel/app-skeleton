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

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserFixtures
{
    public const USERS_DATA = [
        '02432f0b-c33e-4d71-8ba9-a5e3267a45d5' => [
            'firstName' => 'Tony',
            'lastName' => 'Stark',
            'email' => 'ironman@avengers.org',
        ],
        '08acf31d-2e62-44e9-ba18-fd160ac125ad' => [
            'firstName' => 'Wanda Marya',
            'lastName' => 'Maximoff',
            'email' => 'scarlet.witch@avengers.org',
        ],
        '1605a575-77e5-4427-bbdb-2ebcb8cc8033' => [
            'firstName' => 'Peter',
            'lastName' => 'Parker',
            'email' => 'spider.man@avengers.org',
        ],
        '22cd05c9-622d-4dcb-8837-1975e8c08812' => [
            'firstName' => 'Natasha',
            'lastName' => 'Romanoff',
            'email' => 'black.widow@avengers.org',
        ],
        '2a2a63c2-f01a-4b28-b52b-922bd6a170f5' => [
            'firstName' => 'T\'Challa',
            'lastName' => 'King of Wakanda',
            'email' => 'black.panther@avengers.org',
        ],
        '3553b4cf-49ab-4dd6-ba6e-e09b5b96115c' => [
            'firstName' => 'Victor',
            'lastName' => 'Shade',
            'email' => 'vision@avengers.org',
        ],
        '5eefa64f-0800-4fe2-b86f-f3d96bf7d602' => [
            'firstName' => 'Clint',
            'lastName' => 'Barton',
            'email' => 'hawkeye@avengers.org',
        ],
        '7f57d041-a612-4a5a-a61a-e0c96b2c576e' => [
            'firstName' => 'Steve',
            'lastName' => 'Rogers',
            'email' => 'captain@avengers.org',
        ],
        '9f9e9cd2-88bb-438f-b825-b9610c6ee3f4' => [
            'firstName' => 'Loki',
            'lastName' => 'Laufeyson',
            'email' => 'loki@avengers.org',
        ],
        'd24b8b4a-2476-48f7-b865-ee5318d845f3' => [
            'firstName' => 'Thor',
            'lastName' => 'Odinson',
            'email' => 'thor@avengers.org',
        ],
        'fff8bb6d-5772-4e6c-9d10-41d522683264' => [
            'firstName' => 'Bruce',
            'lastName' => 'Banner',
            'email' => 'hulk@avengers.org',
        ],
    ];

    public const ID_OF_NON_EXISTENT_USER = 'eba840cf-9317-4735-b03b-6facfa279890';

    private $userIdsToLoad;

    public function __construct(array $userIdsToLoad = [])
    {
        $this->userIdsToLoad = $userIdsToLoad;
    }

    public static function getNormalizedUsers(): array
    {
        $normalizedUsers = [];

        $userIds = array_keys(self::USERS_DATA);
        foreach ($userIds as $userId) {
            $normalizedUsers[] = static::getNormalizedUser($userId);
        }

        return $normalizedUsers;
    }

    public static function getNormalizedUser(string $userId): array
    {
        return [
            'id' => $userId,
            'firstName' => static::USERS_DATA[$userId]['firstName'],
            'lastName' => static::USERS_DATA[$userId]['lastName'],
            'email' => static::USERS_DATA[$userId]['email'],
        ];
    }
}
