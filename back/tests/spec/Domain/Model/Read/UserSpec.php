<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\App\Domain\Model\Read;

use App\Domain\Model\Read\User;
use App\Tests\Fixtures\UserFixtures;
use PhpSpec\ObjectBehavior;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserSpec extends ObjectBehavior
{
    /** @var array */
    private $userData;

    function let()
    {
        $this->userData = UserFixtures::getNormalizedUsers()[0];
        list($id, $username, $firstName, $lastName) = array_values($this->userData);

        $this->beConstructedWith($id, $username, $firstName, $lastName);
    }

    function it_is_a_user_read_model()
    {
        $this->shouldHaveType(User::class);
    }

    function it_normalizes_itself()
    {
        $this->normalize()->shouldReturn($this->userData);
    }
}
