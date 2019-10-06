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

use Behat\MinkExtension\Context\RawMinkContext;
use Carcel\Tests\Fixtures\UserFixtures;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class ManageUsersContext extends RawMinkContext
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $position
     * @param int    $quantity
     *
     * @When I ask for the :position page of :quantity users
     */
    public function listUsers(string $position, int $quantity): void
    {
        $pageNumber = (int) substr($position, 0, 1);

        $this->visitPath($this->router->generate('rest_users_list', [
            '_page' => $pageNumber,
            '_limit' => $quantity,
        ]));
    }

    /**
     * @When I ask for a specific user
     */
    public function askForASpecificUser(): void
    {
        $uuidList = array_keys(UserFixtures::USERS_DATA);

        $this->visitPath($this->router->generate('rest_users_get', [
            'uuid' => $uuidList[0],
        ]));
    }

    /**
     * @param string $position
     * @param int    $quantity
     *
     * @Then the :position :quantity users should be retrieved
     */
    public function allUsersShouldBeRetrieved(string $position, int $quantity): void
    {
        $responseContent = $this->getSession()->getPage()->getContent();
        $decodedContent = json_decode($responseContent, true);

        $pageNumber = (int) substr($position, 0, 1);

        Assert::same($decodedContent, array_slice(
            UserFixtures::getNormalizedUsers(),
            ($pageNumber - 1) * $quantity,
            $quantity
        ));
    }

    /**
     * @Then the specified user should be retrieved
     */
    public function specifiedUserShouldBeRetrieved(): void
    {
        $uuidList = array_keys(UserFixtures::USERS_DATA);

        $responseContent = $this->getSession()->getPage()->getContent();
        $decodedContent = json_decode($responseContent, true);

        Assert::same(UserFixtures::getNormalizedUser($uuidList[0]), $decodedContent);
    }
}
