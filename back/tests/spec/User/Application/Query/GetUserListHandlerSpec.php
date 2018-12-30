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

namespace spec\Carcel\User\Application\Query;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Application\Query\GetUserList;
use Carcel\User\Application\Query\GetUserListHandler;
use Carcel\User\Domain\Model\Read\UserList;
use Carcel\User\Domain\QueryFunction\GetUserList as GetUserListQueryFunction;
use PhpSpec\ObjectBehavior;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class GetUserListHandlerSpec extends ObjectBehavior
{
    function let(GetUserListQueryFunction $getUserListQueryFunction)
    {
        $this->beConstructedWith($getUserListQueryFunction);
    }

    function it_is_a_get_user_list_query_handler()
    {
        $this->shouldHaveType(GetUserListHandler::class);
    }

    function it_returns_a_list_of_users($getUserListQueryFunction)
    {
        $userList = new UserList(UserFixtures::getNormalizedUsers());

        $getUserListQueryFunction->execute(10, 1)->willReturn($userList);

        $this->handle(new GetUserList(10, 1))->shouldReturn($userList);
    }
}
