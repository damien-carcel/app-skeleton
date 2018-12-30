<?php

declare(strict_types=1);

/*
 * This file is part of SymfonySkeleton.
 *
 * Copyright (c) 2018 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Domain\Repository;

use Carcel\User\Domain\Model\Write\User;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param string $uuid
     *
     * @return User
     */
    public function find(string $uuid): ?User;

    /**
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * @param User $user
     */
    public function delete(User $user): void;
}
