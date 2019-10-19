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

namespace Carcel\User\Application\Command;

use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class DeleteUser
{
    private $identifier;

    public function __construct(UuidInterface $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return UuidInterface
     */
    public function identifier(): UuidInterface
    {
        return $this->identifier;
    }
}
