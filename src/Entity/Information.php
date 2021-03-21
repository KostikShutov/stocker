<?php

declare(strict_types=1);

namespace App\Entity;

interface Information
{
    public function getSlug(): string;

    public function setSlug(string $slug): self;

    public function getTitle(): string;

    public function setTitle(string $title): self;
}
