<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/personToGreet", name="personToGreet")
 */
final class PersonToGreet
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse('World');
    }
}
