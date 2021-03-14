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

namespace Carcel\Tests\Integration\User\Infrastructure\Security;

use Carcel\Tests\Integration\TestCase;
use Carcel\User\Domain\Service\EncodePassword;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class EncodePasswordWithSymfonyEncoderTest extends TestCase
{
    /** @test */
    public function itEncodesAPassword(): void
    {
        $encodedPassword = ($this->encodePassword())('password');

        self::assertStringNotContainsStringIgnoringCase('password', $encodedPassword);
        self::assertStringContainsString('$argon2id$v=19$m=65536,t=4,p=1$', $encodedPassword);
    }

    private function encodePassword(): EncodePassword
    {
        return $this->container()->get(EncodePassword::class);
    }
}
