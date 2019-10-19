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
final class UpdateUserContext implements Context
{
    /**
     * @When I change the name of an existing user
     */
    public function changeTheNameOfAnExistingUser(): void
    {
    }

    /**
     * @Then this user has a new name
     */
    public function userHasANewName(): void
    {
    }
}
