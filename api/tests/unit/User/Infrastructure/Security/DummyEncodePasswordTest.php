<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2020 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\Tests\Unit\User\Infrastructure\Security;

use Carcel\User\Domain\Service\EncodePassword;
use Carcel\User\Infrastructure\Security\DummyEncodePassword;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class DummyEncodePasswordTest extends TestCase
{
    /** @test */
    public function itEncodesAPassword(): void
    {
        static::assertSame('dummy_encoded-<password>', ($this->encodePassword())('password'));
    }

    private function encodePassword(): EncodePassword
    {
        return new DummyEncodePassword();
    }
}
