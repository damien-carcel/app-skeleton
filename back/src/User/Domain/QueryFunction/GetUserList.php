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

namespace Carcel\User\Domain\QueryFunction;

use Carcel\User\Domain\Model\Read\UserList;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
interface GetUserList
{
    /**
     * @todo Use "__invoke" here and in the handlers.
     *
     * @param int $numberOfUsers
     * @param int $userPage
     *
     * @return UserList
     */
    public function execute(int $numberOfUsers, int $userPage): UserList;
}
