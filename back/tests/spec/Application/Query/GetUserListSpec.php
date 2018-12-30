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

namespace spec\App\Application\Query;

use App\Application\Query\GetUserList;
use PhpSpec\ObjectBehavior;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class GetUserListSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(10, 1);
    }

    function it_is_a_get_user_list_query()
    {
        $this->shouldHaveType(GetUserList::class);
    }

    function it_returns_the_number_of_users_the_list_will_contain()
    {
        $this->numberOfUsers()->shouldReturn(10);
    }

    function it_returns_the_user_page()
    {
        $this->userPage()->shouldReturn(1);
    }
}
