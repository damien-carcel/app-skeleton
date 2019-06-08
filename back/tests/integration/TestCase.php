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

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class TestCase extends KernelTestCase
{
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
}
