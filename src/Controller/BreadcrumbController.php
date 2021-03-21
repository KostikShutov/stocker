<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BreadcrumbBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class BreadcrumbController extends AbstractController
{
    private BreadcrumbBuilder $breadcrumbBuilder;

    public function __construct(BreadcrumbBuilder $breadcrumbBuilder)
    {
        $this->breadcrumbBuilder = $breadcrumbBuilder;
    }

    public function renderAction(): Response
    {
        return $this->render('breadcrumb.html.twig', [
            'breadcrumbs' => $this->breadcrumbBuilder->build()
        ]);
    }
}
