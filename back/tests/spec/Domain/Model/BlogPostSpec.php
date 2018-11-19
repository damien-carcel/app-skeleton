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

namespace spec\App\Domain\Model;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class BlogPostSpec extends ObjectBehavior
{
    /** @var UuidInterface */
    private $uuid;

    public function let()
    {
        $this->uuid = Uuid::uuid4();

        $this->beConstructedWith($this->uuid, 'A title', 'A content.');
    }

    function it_has_a_uuid()
    {
        $this->id()->shouldBeAnInstanceOf(Uuid::class);
        $this->id()->shouldBe($this->uuid);
    }

    function it_has_a_title()
    {
        $this->title()->shouldBe('A title');
    }

    function it_as_a_content()
    {
        $this->content()->shouldBe('A content.');
    }

    function it_updates_itself()
    {
        $this->update([
            'title' => 'Foo',
            'content' => 'Bar',
        ]);

        $this->title()->shouldBe('Foo');
        $this->content()->shouldBe('Bar');
    }
}
