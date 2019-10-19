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
final class ChangeUserName
{
    private $uuid;
    private $firstName;
    private $lastName;

    public function __construct(UuidInterface $uuid, string $firstName, string $lastName)
    {
        $this->uuid = $uuid;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return UuidInterface
     */
    public function id(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        return $this->lastName;
    }
}
