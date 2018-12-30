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

namespace App\Tests\EndToEnd\Context;

use App\Tests\Fixtures\UserFixtures;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class ManageUsersContext extends RawMinkContext
{
    /** @var RouterInterface */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @When I ask for the list of the users
     */
    public function listAllTheUsers(): void
    {
        $this->visitPath($this->router->generate('rest_users_list'));
    }

    /**
     * @Then all the users should be retrieved
     */
    public function allUsersShouldBeRetrieved(): void
    {
        $responseContent = $this->getSession()->getPage()->getContent();
        $decodedContent = json_decode($responseContent, true);

        Assert::same($decodedContent, UserFixtures::getNormalizedUsers());
    }
}