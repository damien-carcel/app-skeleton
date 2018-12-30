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

namespace spec\App\Infrastructure\QueryFunction\Doctrine;

use App\Domain\QueryFunction\GetUserList;
use App\Infrastructure\QueryFunction\Doctrine\GetUserListFromDatabase;
use Doctrine\DBAL\Connection;
use PhpSpec\ObjectBehavior;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class GetUserListFromDatabaseSpec extends ObjectBehavior
{
    function let(Connection $connection)
    {
        $this->beConstructedWith($connection);
    }

    function it_is_a_get_user_list_query()
    {
        $this->shouldHaveType(GetUserListFromDatabase::class);
        $this->shouldImplement(GetUserList::class);
    }
}
