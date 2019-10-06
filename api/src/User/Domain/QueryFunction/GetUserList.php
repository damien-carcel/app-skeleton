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

namespace Carcel\User\Domain\QueryFunction;

use Carcel\User\Domain\Model\Read\UserList;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
interface GetUserList
{
    public function __invoke(int $numberOfUsers, int $userPage): UserList;
}
