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
    private $email;

    private function __construct(string $email)
    {
        Assert::notEmpty($email, 'User email cannot be empty.');
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
