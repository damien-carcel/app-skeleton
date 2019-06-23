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

namespace Carcel\User\Application\Query;

use Carcel\User\Domain\Model\Read\UserList;
use Carcel\User\Domain\QueryFunction\GetUserList as GetUserListQueryFunction;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserListHandler
{
    private $getUserListQueryFunction;

    public function __construct(GetUserListQueryFunction $getUserListQueryFunction)
    {
        $this->getUserListQueryFunction = $getUserListQueryFunction;
    }

    public function __invoke(GetUserList $getUserList): UserList
    {
        return ($this->getUserListQueryFunction)(
            $getUserList->numberOfUsers(),
            $getUserList->userPage()
        );
    }
}
