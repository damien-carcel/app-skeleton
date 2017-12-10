<?php

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class IndexController
{
    /** @var string */
    private $projectDir;

    /**
     * @param string $projectDir
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        $pathToHtmlIndex = $this->projectDir.'/public/index.html';

        $html = file_get_contents($pathToHtmlIndex);

        return new Response($html);
    }
}
