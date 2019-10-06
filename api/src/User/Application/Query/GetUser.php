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

namespace Carcel\User\Application\Query;

use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUser
{
    private $userIdentifier;

    public function __construct(UuidInterface $userIdentifier)
    {
        $this->userIdentifier = $userIdentifier;
    }

    public function userIdentifier(): UuidInterface
    {
        return $this->userIdentifier;
    }
}
