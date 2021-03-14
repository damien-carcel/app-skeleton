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

use Behat\Behat\Context\Context;
use Carcel\Tests\Fixtures\UserFixtures;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserCollectionContext implements Context
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
     * @When I ask for the :position page of :quantity users
     */
    public function listUsers(string $position, int $quantity): void
    {
        $pageNumber = (int) substr($position, 0, 1);

        $this->client->request(
            'GET',
            $this->router->generate('api_users_collection_get', [
                '_page' => $pageNumber,
                '_limit' => $quantity,
            ]),
        );
    }

    /**
     * @Then the :position :quantity users should be retrieved
     */
    public function allUsersShouldBeRetrieved(string $position, int $quantity): void
    {
        $response = $this->client->getResponse();
        Assert::same($response->getStatusCode(), 200);

        $responseContent = $response->getContent();
        $decodedContent = json_decode($responseContent, true);

        $pageNumber = (int) substr($position, 0, 1);

        Assert::same($decodedContent, array_slice(
            UserFixtures::getNormalizedUsers(),
            ($pageNumber - 1) * $quantity,
            $quantity,
        ));
    }
}
