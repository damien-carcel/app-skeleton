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

use Behat\Behat\Context\Context;
use Carcel\Tests\Fixtures\UserFixtures;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserContext implements Context
{
    private Connection $connection;
    private KernelBrowser $client;
    private RouterInterface $router;

    public function __construct(Connection $connection, KernelBrowser $client, RouterInterface $router)
    {
        $this->connection = $connection;
        $this->client = $client;
        $this->router = $router;
    }

    /**
     * @When I ask for a specific user
     */
    public function askForASpecificUser(): void
    {
        $this->client->request(
            'GET',
            $this->router->generate('api_users_get', [
                'id' => array_keys(UserFixtures::USERS_DATA)[0],
            ]),
        );
    }

    /**
     * @Then the specified user should be retrieved
     */
    public function specifiedUserShouldBeRetrieved(): void
    {
        Assert::same($this->client->getResponse()->getStatusCode(), 200);

        $uuidList = array_keys(UserFixtures::USERS_DATA);

        $responseContent = $this->client->getResponse()->getContent();
        $decodedContent = json_decode($responseContent, true);

        Assert::keyExists($decodedContent, 'id');
        Assert::same($decodedContent['id'], UserFixtures::getNormalizedUser($uuidList[0])['id']);
        Assert::keyExists($decodedContent, 'email');
        Assert::same($decodedContent['email'], UserFixtures::getNormalizedUser($uuidList[0])['email']);
        Assert::keyExists($decodedContent, 'firstName');
        Assert::same($decodedContent['firstName'], UserFixtures::getNormalizedUser($uuidList[0])['firstName']);
        Assert::keyExists($decodedContent, 'lastName');
        Assert::same($decodedContent['lastName'], UserFixtures::getNormalizedUser($uuidList[0])['lastName']);
    }
}
