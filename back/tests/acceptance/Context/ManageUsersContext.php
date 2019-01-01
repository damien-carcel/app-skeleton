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

namespace Carcel\Tests\Acceptance\Context;

use Behat\Behat\Context\Context;
use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Application\Query\GetUserList as GetUserListQuery;
use Carcel\User\Application\Query\GetUserListHandler;
use Carcel\User\Domain\Model\Read\UserList;
use Webmozart\Assert\Assert;

/**
 * This is a very bad acceptance test context, as it makes use of the framework
 * (router, request handling), which is not business. This is only for demo.
 *
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class ManageUsersContext implements Context
{
    /** @var GetUserListHandler */
    private $getUserList;

    /** @var UserList */
    private $userList;

    /**
     * @param GetUserListHandler $getUserList
     */
    public function __construct(GetUserListHandler $getUserList)
    {
        $this->getUserList = $getUserList;
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

        $this->userList = $this->getUserList->handle(new GetUserListQuery($quantity, $pageNumber));
    }

    /**
     * @param string $position
     * @param int    $quantity
     *
     * @Then the :position :quantity users should be retrieved
     */
    public function allUsersShouldBeRetrieved(string $position, int $quantity): void
    {
        $pageNumber = (int) substr($position, 0, 1);

        Assert::same($this->userList->normalize(), array_slice(
            UserFixtures::getNormalizedUsers(),
            ($pageNumber - 1) * $quantity,
            $quantity
        ));
    }
}
