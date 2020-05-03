<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2020 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Domain\QueryFunction;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
interface IsEmailAlreadyUsed
{
    public function __invoke(string $email): bool;
}
