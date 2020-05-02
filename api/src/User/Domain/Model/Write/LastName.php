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
final class LastName
{
    private const LAST_NAME_MAX_LENGTH = 256;

    private string $lastName;

    private function __construct(string $lastName)
    {
        Assert::notEmpty($lastName, 'The user last name cannot be empty.');
        Assert::maxLength($lastName, static::LAST_NAME_MAX_LENGTH, sprintf(
            'The last name should not have more than %d characters, "%s" is %d characters long.',
            static::LAST_NAME_MAX_LENGTH,
            $lastName,
            strlen($lastName)
        ));

        $this->lastName = $lastName;
    }

    public function __toString(): string
    {
        return $this->lastName;
    }

    public static function fromString(string $firstName): self
    {
        return new self($firstName);
    }
}
