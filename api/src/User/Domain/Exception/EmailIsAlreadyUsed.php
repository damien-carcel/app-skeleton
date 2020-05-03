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

namespace Carcel\User\Domain\Exception;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class EmailIsAlreadyUsed extends \InvalidArgumentException
{
    private function __construct(string $email)
    {
        parent::__construct(sprintf('The email "%s" is already used by another user', $email));
    }

    public static function fromEmail(string $email): self
    {
        return new self($email);
    }
}
