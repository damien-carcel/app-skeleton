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
use Carcel\User\Application\Query\GetUserList as GetUserListQuery;
use Carcel\User\Domain\Model\Read\UserList;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class ListUsersContext implements Context
{
    private Envelope $userListEnvelope;

    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @When I ask for the :position page of :quantity users
     */
    public function listUsers(string $position, int $quantity): void
    {
        $getUserList = new GetUserListQuery();
        $getUserList->numberOfUsers = $quantity;
        $getUserList->userPage = (int) substr($position, 0, 1);

        $this->userListEnvelope = $this->bus->dispatch($getUserList);
    }

    /**
     * @Then the :position :quantity users should be retrieved
     */
    public function allUsersShouldBeRetrieved(string $position, int $quantity): void
    {
        $pageNumber = (int) substr($position, 0, 1);

        Assert::same($this->getQueriedUserList()->normalize(), array_slice(
            UserFixtures::getNormalizedUsers(),
            ($pageNumber - 1) * $quantity,
            $quantity
        ));
    }

    private function getQueriedUserList(): UserList
    {
        $handledStamp = $this->userListEnvelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}
