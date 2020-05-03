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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class EncodePasswordWithSymfonyEncoder implements EncodePassword
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * We pass an empty security user, as the generic Symfony encoder only need
     * it to find the right encoder implementation according to its class, then
     * needs to get the salt (which is null as we use sodium) to pass it with the
     * password to the encoder implementation.
     */
    public function __invoke(string $plainPassword): string
    {
        return $this->passwordEncoder->encodePassword(new User('', '', []), $plainPassword);
    }
}
