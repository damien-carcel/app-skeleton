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
use Carcel\User\Application\Query\GetUser;
use Carcel\User\Domain\Exception\UserDoesNotExist;
use Carcel\User\Domain\Model\Read\User;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserContext implements Context
{
    /** @var Envelope */
    private $userEnvelope;

    /** @var HandlerFailedException */
    private $caughtException;

    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @When I ask for a specific user
     */
    public function askForASpecificUser(): void
    {
        $uuidList = array_keys(UserFixtures::USERS_DATA);

        $this->userEnvelope = $this->bus->dispatch(new GetUser($uuidList[0]));
    }

    /**
     * @When I ask for a user that does not exist
     */
    public function askForAUserThatDoesNotExist(): void
    {
        try {
            $this->userEnvelope = $this->bus->dispatch(new GetUser(UserFixtures::ID_OF_NON_EXISTENT_USER));
        } catch (\Exception $exception) {
            $this->caughtException = $exception;
        }
    }

    /**
     * @Then the specified user should be retrieved
     */
    public function specifiedUserShouldBeRetrieved(): void
    {
        $uuidList = array_keys(UserFixtures::USERS_DATA);

        Assert::same(
            UserFixtures::getNormalizedUser($uuidList[0]),
            $this->getQueriedUserList()->normalize()
        );
    }

    /**
     * @Then I got no user
     */
    public function gotNoUser(): void
    {
        Assert::isInstanceOf($this->caughtException, HandlerFailedException::class);
        $handledExceptions = $this->caughtException->getNestedExceptions();

        Assert::count($handledExceptions, 1);
        Assert::isInstanceOf(current($handledExceptions), UserDoesNotExist::class);
    }

    private function getQueriedUserList(): User
    {
        $handledStamp = $this->userEnvelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}
