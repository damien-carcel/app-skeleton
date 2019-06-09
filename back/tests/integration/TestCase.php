<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2019 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\Tests\Integration;

use Carcel\Tests\Fixtures\UserFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class TestCase extends KernelTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        self::bootKernel(['debug' => false, 'environment' => 'integration']);
    }

    /**
     * Using this special test container allows to get private services,
     * but only if they are already injected somewhere in the application.
     */
    protected function container(): ContainerInterface
    {
        return self::$container;
    }

    /**
     * @param array $usersIdsToLoad
     */
    protected function loadUserFixtures(array $usersIdsToLoad = []): void
    {
        $entityManager = $this->container()->get('doctrine.orm.entity_manager');

        $purger = new ORMPurger($entityManager);
        $executor = new ORMExecutor($entityManager, $purger);

        $executor->execute([new UserFixtures($usersIdsToLoad)]);
    }
}
