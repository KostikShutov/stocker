<?php

declare(strict_types=1);

namespace App\Service;

final class BreadcrumbBuilder
{
    private array $breadcrumbs = [];

    private bool $isBuild = false;

    public function build(): array
    {
        $this->isBuild = true;

        return $this->breadcrumbs;
    }

    public function addToBuild(?string $route, string $name): void
    {
        if (!$this->isBuild) {
            array_unshift($this->breadcrumbs, [
                'route' => $route,
                'name'  => $name
            ]);
        }
    }
}
