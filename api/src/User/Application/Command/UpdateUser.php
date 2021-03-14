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

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class UpdateUser
{
    public string $identifier;
    public string $firstName;
    public string $lastName;
    public string $email;

    public function __construct(
        string $identifier,
        string $firstName,
        string $lastName,
        string $email
    ) {
        $this->identifier = $identifier;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }
}
