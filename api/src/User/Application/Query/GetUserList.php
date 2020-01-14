<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Application\Query;

/**
 * Queries a list of users.
 *
 * The list will contains a certain number of users, starting to a specific page.
 * A page contains the number of users specified in the query. For instance, if
 * the number of users is 10, page 2 will start with the 11th user and end with
 * the 20th.
 *
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserList
{
    private int $numberOfUsers;
    private int $userPage;

    public function __construct(int $numberOfUsers, int $userPage)
    {
        $this->numberOfUsers = $numberOfUsers;
        $this->userPage = $userPage;
    }

    public function numberOfUsers(): int
    {
        return $this->numberOfUsers;
    }

    public function userPage(): int
    {
        return $this->userPage;
    }
}
