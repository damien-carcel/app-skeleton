<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2019 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Domain\Model\Write;

use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class FirstName
{
    private const FIRST_NAME_MAX_LENGTH = 256;

    private string $firstName;

    private function __construct(string $firstName)
    {
        Assert::notEmpty($firstName, 'The user first name cannot be empty.');
        Assert::maxLength($firstName, self::FIRST_NAME_MAX_LENGTH, sprintf(
            'The first name should not have more than %d characters, "%s" is %d characters long.',
            self::FIRST_NAME_MAX_LENGTH,
            $firstName,
            strlen($firstName)
        ));

        $this->firstName = $firstName;
    }

    public function __toString(): string
    {
        return $this->firstName;
    }

    public static function fromString(string $firstName): self
    {
        return new self($firstName);
    }
}
