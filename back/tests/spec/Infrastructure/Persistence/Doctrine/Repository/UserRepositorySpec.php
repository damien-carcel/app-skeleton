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

namespace spec\App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class UserRepositorySpec extends ObjectBehavior
{
    function it_is_initializable(EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($entityManager);

        $this->shouldImplement(UserRepositoryInterface::class);
        $this->shouldBeAnInstanceOf(UserRepository::class);
    }
}
