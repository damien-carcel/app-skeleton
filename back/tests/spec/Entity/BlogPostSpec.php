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

namespace spec\App\Entity;

use App\Entity\BlogPost;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class BlogPostSpec extends ObjectBehavior
{
    function it_is_a_blog_post()
    {
        $this->shouldBeAnInstanceOf(BlogPost::class);
    }

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
