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

namespace Carcel\User\Domain\Exception;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UserDoesNotExist extends \InvalidArgumentException
{
    private function __construct(string $identifier)
    {
        parent::__construct(sprintf('There is no user with identifier "%s"', $identifier));
    }

    public static function fromUuid(string $uuid): self
    {
        return new self($uuid);
    }
}
