<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Domain\Model\Read;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class User
{
    /** @var string */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /**
     * @param string $id
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(string $id, string $username, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return array
     */
    public function normalize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
