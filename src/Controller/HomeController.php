<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\Breadcrumb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    const TITLE_HOME = 'Главная страница';

    /**
     * @Route(name="home", path="/")
     * @Breadcrumb(name=self::TITLE_HOME)
     */
    public function homeAction(): Response
    {
        return $this->render('home/home.html.twig', [
            'title' => self::TITLE_HOME
        ]);
    }
}
