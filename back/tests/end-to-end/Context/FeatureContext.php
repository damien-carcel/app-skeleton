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
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class FeatureContext extends MinkContext implements Context
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
     * @When a request asks for the list of users
     */
    public function listAllTheUsers(): void
    {
        $this->visitPath($this->router->generate('rest_users_list'));
    }

    /**
     * @return bool
     *
     * @Then all the users should be retrieved
     */
    public function allUsersShouldBeRetrieved(): bool
    {
        $responseContent = $this->getSession()->getPage()->getContent();
        $decodedContent = json_decode($responseContent, true);

        $filteredContent = array_filter($decodedContent, function ($user) {
            unset($user['id']);

            return $user;
        });

        if (UserFixtures::NORMALIZED_USERS !== $filteredContent) {
            return false;
        }

        return true;
    }
}
