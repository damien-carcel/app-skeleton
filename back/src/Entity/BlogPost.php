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

use Ramsey\Uuid\UuidInterface;

/**
 * @author Damien Carcel <damien.carcel@gmail.com>
 */
class BlogPost
{
    /** @var UuidInterface */
    private $id;

    /** @var string */
    private $title = '';

    /** @var string */
    private $content = '';

    /**
     * @return UuidInterface
     */
    public function id(): UuidInterface
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
        if (array_key_exists('id', $data) && null === $this->id) {
            $this->id = $data['id'];
        }

        if (array_key_exists('title', $data)) {
            $this->title = $data['title'];
        }

        if (array_key_exists('content', $data)) {
            $this->content = $data['content'];
        }
    }
}
