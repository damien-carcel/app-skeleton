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
final class Email
{
    private const EMAIL_MAX_LENGTH = 256;

    private string $email;

    private function __construct(string $email)
    {
        Assert::notEmpty($email, 'The user email cannot be empty.');
        Assert::maxLength($email, static::EMAIL_MAX_LENGTH, sprintf(
            'The email address should not have more than %d characters, "%s" is %d characters long.',
            static::EMAIL_MAX_LENGTH,
            $email,
            strlen($email)
        ));
        Assert::email($email, sprintf('The use email "%s" is not a valid email address.', $email));

        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }
}
