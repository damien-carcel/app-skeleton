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

namespace App\Tests\Acceptance\Context;

use App\Tests\Fixtures\UserFixtures;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

/**
 * This is a very bad acceptance test context, as it makes use of the framework
 * (router, request handling), which is not business. This is only for demo.
 *
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class FeatureContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    /** @var Response|null */
    private $response;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param string $path
     *
     * @throws \Exception
     *
     * @When a request is sent to :path
     */
    public function aRequestIsSentTo(string $path): void
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then a response should be received
     */
    public function aResponseShouldBeReceived(): void
    {
        Assert::notNull($this->response);
    }

    /**
     * @throws \Exception
     *
     * @When a request asks for the list of users
     */
    public function listAllTheUsers(): void
    {
        $router = $this->kernel->getContainer()->get('router');
        $path = $router->generate('rest_users_list');

        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @throws \RuntimeException
     *
     * @return bool
     *
     * @Then all the users should be retrieved
     */
    public function allUsersShouldBeRetrieved(): bool
    {
        $jsonResponse = $this->response->getContent();

        $users = json_decode($jsonResponse, true);

        if ($users !== array_values(UserFixtures::NORMALIZED_USERS)) {
            return false;
        }

        return true;
    }
}
