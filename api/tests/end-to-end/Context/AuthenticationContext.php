<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\Tests\EndToEnd\Context;

use Behat\Behat\Context\Context;
use Carcel\User\Infrastructure\Security\UserProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class AuthenticationContext implements Context
{
    public static string $TOKEN = '';

    private UserProvider $userProvider;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(UserProvider $userProvider, JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
        $this->userProvider = $userProvider;
    }

    /**
     * @Given I am logged as an administrator
     */
    public function iAmLoggedAsAnAdministrator(): void
    {
        $user = $this->userProvider->loadUserByUsername('ironman@avengers.org');

        static::$TOKEN = $this->jwtManager->create($user);
    }

    /**
     * @AfterScenario
     */
    public function logout(): void
    {
        static::$TOKEN = '';
    }
}
