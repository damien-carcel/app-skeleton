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

namespace Carcel\Tests\Acceptance\Context;

use Behat\Behat\Context\Context;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class DeleteUserContext implements Context
{
    /**
     * @When I delete a user
     */
    public function askForASpecificUser(): void
    {
    }

    /**
     * @Then the user is deleted
     */
    public function specifiedUserShouldBeRetrieved(): void
    {
    }
}
