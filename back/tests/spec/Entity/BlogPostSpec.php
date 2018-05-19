<?php

namespace spec\App\Entity;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class BlogPostSpec extends ObjectBehavior
{
    function it_updates_itself()
    {
        $this->update([
            'id' => Uuid::uuid4(),
            'title' => 'Foo',
            'content' => 'Bar',
        ]);

        $this->id()->shouldBeAnInstanceOf(Uuid::class);
        $this->title()->shouldBe('Foo');
        $this->content()->shouldBe('Bar');
    }
}
