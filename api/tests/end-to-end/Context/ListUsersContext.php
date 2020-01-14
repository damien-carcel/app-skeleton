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
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class ListUsersContext implements Context
{
    private ResponseInterface $response;

    private KernelInterface $kernel;
    private RouterInterface $router;

    public function __construct(KernelInterface $kernel, RouterInterface $router)
    {
        $this->kernel = $kernel;
        $this->router = $router;
    }

    /**
     * @When I ask for the :position page of :quantity users
     */
    public function listUsers(string $position, int $quantity): void
    {
        $pageNumber = (int) substr($position, 0, 1);

        $this->response = $this->client()->request(
            'GET',
            $this->router->generate('rest_users_list', [
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
        Assert::same($this->response->getStatusCode(), 200);

        $responseContent = $this->response->getContent();
        $decodedContent = json_decode($responseContent, true);

        $pageNumber = (int) substr($position, 0, 1);

        Assert::same($decodedContent, array_slice(
            UserFixtures::getNormalizedUsers(),
            ($pageNumber - 1) * $quantity,
            $quantity,
        ));
    }

    private function client(): HttpClientInterface
    {
        return $this->kernel->getContainer()->get('test.api_platform.client');
    }
}
