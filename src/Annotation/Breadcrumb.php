<?php

declare(strict_types=1);

namespace App\Annotation;

/**
 * @Annotation
 */
class Breadcrumb
{
    public string $route = '';

    public string $name;
}
