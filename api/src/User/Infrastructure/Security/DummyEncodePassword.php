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

namespace Carcel\User\Infrastructure\Security;

use Carcel\User\Domain\Service\EncodePassword;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class DummyEncodePassword implements EncodePassword
{
    public function __invoke(string $plainPassword): string
    {
        return sprintf('dummy_encoded-<%s>', $plainPassword);
    }
}
