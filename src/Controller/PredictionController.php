<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\Breadcrumb;
use App\Service\PredictionFilterService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route(name="prediction_", path="/prediction/")
 */
final class PredictionController extends AbstractController
{
    const TITLE_LIST = 'История';

    private PredictionFilterService $predictionFilterService;

    public function __construct(PredictionFilterService $predictionFilterService)
    {
        $this->predictionFilterService = $predictionFilterService;
    }

    /**
     * @Route(name="list", path="list")
     * @Breadcrumb(route="home", name=self::TITLE_LIST)
     */
    public function listAction(Request $request): Response
    {
        $process = (int) $request->query->get('show');

        return $this->render('prediction/list.html.twig', [
            'title'       => self::TITLE_LIST,
            'filters'     => $this->predictionFilterService->getFilters(),
            'predictions' => $this->predictionFilterService->getPredictionsByProcess($process)
        ]);
    }
}
