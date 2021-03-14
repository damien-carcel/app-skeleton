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

use Carcel\User\Domain\Model\Read\UserCollection;
use Carcel\User\Domain\QueryFunction\GetUserCollection as GetUserCollectionQueryFunction;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserCollectionHandler implements MessageHandlerInterface
{
    private GetUserCollectionQueryFunction $getUserCollectionQueryFunction;

    public function __construct(GetUserCollectionQueryFunction $getUserCollectionQueryFunction)
    {
        $this->getUserCollectionQueryFunction = $getUserCollectionQueryFunction;
    }

    public function __invoke(GetUserCollection $getUserCollection): UserCollection
    {
        return ($this->getUserCollectionQueryFunction)(
            $getUserCollection->numberOfUsers(),
            $getUserCollection->userPage(),
        );
    }
}
