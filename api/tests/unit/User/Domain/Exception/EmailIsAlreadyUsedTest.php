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

namespace Carcel\Tests\Unit\User\Domain\Exception;

use Carcel\User\Domain\Exception\EmailIsAlreadyUsed;
use PHPUnit\Framework\TestCase;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class EmailIsAlreadyUsedTest extends TestCase
{
    /** @test */
    public function itReturnsAMessage(): void
    {
        $exception = $this->instantiateEmailIsAlreadyUsed();

        static::assertSame(
            'The email "batman@justiceligue.org" is already used by another user',
            $exception->getMessage(),
        );
    }

    private function instantiateEmailIsAlreadyUsed(): EmailIsAlreadyUsed
    {
        return EmailIsAlreadyUsed::fromEmail('batman@justiceligue.org');
    }
}
