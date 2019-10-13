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

namespace Carcel\Tests\EndToEnd\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Carcel\Tests\Fixtures\UserFixtures;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserContext extends RawMinkContext
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
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
