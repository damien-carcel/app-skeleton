<?php

declare(strict_types=1);

/*
 * This file is part of AppSkeleton.
 *
 * Copyright (c) 2017 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class BlogPost
{
    /** @var int */
    private $id;

    /** @var string */
    private $title = '';

    /** @var string */
    private $content = '';

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * @param array $data
     */
    public function update(array $data): void
    {
        if ($data['title']) {
            $this->setTitle($data['title']);
        }

        if ($data['content']) {
            $this->setContent($data['content']);
        }
    }

    /**
     * @param string $title
     */
    private function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $content
     */
    private function setContent(string $content): void
    {
        $this->content = $content;
    }
}
