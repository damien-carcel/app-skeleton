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

namespace Carcel\Tests\Acceptance\Context;

use Behat\Behat\Context\Context;
use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Application\Query\GetUserCollection as GetUserCollectionQuery;
use Carcel\User\Application\Query\GetUserCollectionHandler;
use Carcel\User\Domain\Model\Read\UserCollection;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserCollectionContext implements Context
{
    private UserCollection $userCollection;

    private GetUserCollectionHandler $handler;

    public function __construct(GetUserCollectionHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @When I ask for the :position page of :quantity users
     */
    public function listUsers(string $position, int $quantity): void
    {
        $getUserCollection = new GetUserCollectionQuery($quantity, (int) substr($position, 0, 1));

        $this->userCollection = ($this->handler)($getUserCollection);
    }

    /**
     * @Then the :position :quantity users should be retrieved
     */
    public function allUsersShouldBeRetrieved(string $position, int $quantity): void
    {
        $pageNumber = (int) substr($position, 0, 1);

        Assert::same($this->userCollection->normalize(), array_slice(
            UserFixtures::getNormalizedUsers(),
            ($pageNumber - 1) * $quantity,
            $quantity
        ));
    }
}
