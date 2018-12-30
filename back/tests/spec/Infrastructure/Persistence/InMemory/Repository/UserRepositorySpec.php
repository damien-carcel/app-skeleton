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

namespace spec\App\Infrastructure\Persistence\InMemory\Repository;

use App\Domain\Model\Write\User;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserRepositorySpec extends ObjectBehavior
{
    /** @var UuidInterface[] */
    private $userIDs;

    /** @var User[] */
    private $users;

    function let()
    {
        $this->userIDs = [Uuid::uuid4(), Uuid::uuid4()];
        $this->users = [
            new User($this->userIDs[0], 'foobar', 'foo', 'bar', 'pass', 'salt', []),
            new User($this->userIDs[1], 'barbaz', 'bar', 'baz', 'pass', 'salt', []),
        ];

        $this->beConstructedWith($this->users);
    }

    function it_find_all_users()
    {
        $this->findAll()->shouldReturn($this->users);
    }

    function it_find_a_user_from_its_id()
    {
        $this->find((string) $this->userIDs[0])->shouldReturn($this->users[0]);
    }

    function it_saves_a_user()
    {
        $userId = Uuid::uuid4();
        $user = new User($userId, 'foobarbaz', 'foobar', 'barbaz', 'pass', 'salt', []);

        $this->save($user);

        $this->findAll()->shouldHaveCount(3);
        $this->find((string) $userId)->shouldReturn($user);
    }

    function it_deletes_a_user()
    {
        $this->delete($this->users[0]);

        $this->findAll()->shouldHaveCount(1);
        $this->find((string) $this->userIDs[0])->shouldReturn(null);
    }
}
