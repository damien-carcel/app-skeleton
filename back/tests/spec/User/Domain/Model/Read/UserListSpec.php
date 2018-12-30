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

namespace spec\Carcel\User\Domain\Model\Read;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Model\Read\UserList;
use PhpSpec\ObjectBehavior;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserListSpec extends ObjectBehavior
{
    function it_is_a_user_list()
    {
        $usersData = UserFixtures::getNormalizedUsers();
        $this->beConstructedWith($usersData);

        $this->shouldHaveType(UserList::class);
    }

    function it_is_an_empty_user_list()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType(UserList::class);
    }

    function it_normalizes_itself()
    {
        $usersData = UserFixtures::getNormalizedUsers();
        $this->beConstructedWith($usersData);

        $this->normalize()->shouldReturn($usersData);
    }
}
