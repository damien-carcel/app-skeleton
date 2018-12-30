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

namespace spec\App\Domain\Model\Write;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserSpec extends ObjectBehavior
{
    /** @var UuidInterface */
    private $uuid;

    public function let()
    {
        $this->uuid = Uuid::uuid4();

        $this->beConstructedWith($this->uuid, 'ironman', 'Tony', 'Stark', 'password', 'salt', []);
    }

    function it_has_a_uuid()
    {
        $this->id()->shouldBeAnInstanceOf(Uuid::class);
        $this->id()->shouldBe($this->uuid);
    }

    function it_returns_the_user_username()
    {
        $this->getUsername()->shouldReturn('ironman');
    }

    function it_returns_the_user_fisrt_name()
    {
        $this->getFirstName()->shouldReturn('Tony');
    }

    function it_returns_the_user_last_name()
    {
        $this->getLastName()->shouldReturn('Stark');
    }

    function it_returns_the_user_password()
    {
        $this->getPassword()->shouldReturn('password');
    }

    function it_returns_the_user_salt_key()
    {
        $this->getSalt()->shouldReturn('salt');
    }

    function it_returns_the_user_roles()
    {
        $this->getRoles()->shouldReturn([]);
    }
}
