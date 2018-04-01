<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\System\Context;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class FeatureContext extends MinkContext implements KernelAwareContext
{
    /** @var KernelInterface */
    private $kernel;

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel): void
    {
        $this->kernel = $kernel;
    }

    /**
     * @param string $path
     *
     * @throws \Exception
     *
     * @When a request is sent to :path
     */
    public function aRequestIsSentTo(string $path): void
    {
        $this->visitPath($path);
    }

    /**
     * @throws \RuntimeException
     *
     * @Then a response should be received
     */
    public function aResponseShouldBeReceived(): void
    {
        $this->assertResponseContains('');
    }
}
