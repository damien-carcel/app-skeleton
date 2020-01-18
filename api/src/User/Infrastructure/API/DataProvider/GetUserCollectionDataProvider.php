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

namespace Carcel\User\Infrastructure\API\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Carcel\User\Application\Query\GetUserCollection;
use Carcel\User\Application\Query\GetUserCollectionHandler;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
final class GetUserCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private GetUserCollectionHandler $handler;

    public function __construct(GetUserCollectionHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): array
    {
        $getUserCollection = new GetUserCollection();
        $getUserCollection->numberOfUsers = (int) $context['filters']['_limit'];
        $getUserCollection->userPage = (int) $context['filters']['_page'];

        $userCollection = ($this->handler)($getUserCollection);

        return $userCollection->normalize();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return GetUserCollection::class === $resourceClass;
    }
}
