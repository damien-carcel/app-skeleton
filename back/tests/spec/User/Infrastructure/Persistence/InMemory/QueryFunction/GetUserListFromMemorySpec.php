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

namespace spec\Carcel\User\Infrastructure\Persistence\InMemory\QueryFunction;

use Carcel\Tests\Fixtures\UserFixtures;
use Carcel\User\Domain\Model\Read\UserList;
use Carcel\User\Domain\QueryFunction\GetUserList;
use Carcel\User\Domain\Repository\UserRepositoryInterface;
use Carcel\User\Infrastructure\Persistence\InMemory\QueryFunction\GetUserListFromMemory;
use Carcel\User\Infrastructure\Persistence\InMemory\Repository\UserRepository;
use PhpSpec\ObjectBehavior;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class GetUserListFromMemorySpec extends ObjectBehavior
{
    function let()
    {
        $repository = $this->instantiateInMemoryUserRepository();

        $this->beConstructedWith($repository);
    }

    function it_is_a_get_user_list_query()
    {
        $this->shouldHaveType(GetUserListFromMemory::class);
        $this->shouldImplement(GetUserList::class);
    }

    function it_gets_a_list_of_users()
    {
        $userList = $this->execute(10, 1);
        $userList->shouldHaveType(UserList::class);
        $userList->normalize()->shouldReturn(array_slice(UserFixtures::getNormalizedUsers(), 0, 10));
    }

    function it_gets_a_limited_list_of_users()
    {
        $userList = $this->execute(2, 1);
        $userList->shouldHaveType(UserList::class);
        $userList->normalize()->shouldReturn(array_slice(UserFixtures::getNormalizedUsers(), 0, 2));
    }

    function it_gets_a_list_of_one_user_starting_a_certain_page()
    {
        $userList = $this->execute(1, 2);
        $userList->shouldHaveType(UserList::class);
        $userList->normalize()->shouldReturn(array_slice(UserFixtures::getNormalizedUsers(), 1, 2));
    }

    function it_gets_a_list_of_users_starting_a_certain_page()
    {
        $userList = $this->execute(5, 2);
        $userList->shouldHaveType(UserList::class);
        $userList->normalize()->shouldReturn(array_slice(UserFixtures::getNormalizedUsers(), 5, 10));
    }

    function it_gets_an_empty_list_of_users_if_the_page_is_too_high()
    {
        $userList = $this->execute(10, 3);
        $userList->shouldHaveType(UserList::class);
        $userList->normalize()->shouldReturn([]);
    }

    /**
     * @return UserRepositoryInterface
     */
    private function instantiateInMemoryUserRepository(): UserRepositoryInterface
    {
        $repository = new UserRepository();

        $users = UserFixtures::instantiateUserEntities();
        foreach ($users as $user) {
            $repository->save($user);
        }

        return $repository;
    }
}
