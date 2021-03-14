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

namespace Carcel\User\Domain\Model\Write;

use Webmozart\Assert\Assert;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class Password
{
    private const PASSWORD_MAX_LENGTH = 256;

    private string $password;

    private function __construct(string $password)
    {
        Assert::notEmpty($password, 'The user password cannot be empty.');
        Assert::maxLength($password, self::PASSWORD_MAX_LENGTH, sprintf(
            'The password should not have more than %d characters, "%s" is %d characters long.',
            self::PASSWORD_MAX_LENGTH,
            $password,
            strlen($password)
        ));

        $this->password = $password;
    }

    public function __toString(): string
    {
        return $this->password;
    }

    public static function fromString(string $password): self
    {
        return new self($password);
    }
}
