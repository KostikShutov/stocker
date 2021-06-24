<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Process;
use App\Annotation\Breadcrumb;
use App\Repository\ProcessRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route(name="process_", path="/process/")
 */
final class ProcessController extends AbstractController
{
    const TITLE_SHOW = 'Просмотр прогноза';
    const TITLE_LIST = 'Очередь прогнозов';

    private ProcessRepository $processRepository;

    public function __construct(ProcessRepository $processRepository)
    {
        $this->processRepository = $processRepository;
    }

    /**
     * @Route(name="show", path="show/{id}", requirements={"id"="\d+"})
     * @Breadcrumb(route="method_list", name=self::TITLE_SHOW)
     */
    public function showAction(int $id): Response
    {
        $process = $this->processRepository->find($id);

        if (is_null($process)) {
            throw $this->createNotFoundException();
        }

        return $this->render('process/show.html.twig', [
            'title'   => self::TITLE_SHOW,
            'process' => $process
        ]);
    }

    /**
     * @Route(name="list", path="list")
     * @Breadcrumb(route="home", name=self::TITLE_LIST)
     */
    public function listAction(): Response
    {
        $processes = $this->processRepository->findBy([], ['id' => 'DESC']);

        return $this->render('process/list.html.twig', [
            'title'     => self::TITLE_LIST,
            'processes' => $processes
        ]);
    }

    /**
     * @Route(name="check", path="check/{id}", requirements={"id"="\d+"})
     */
    public function checkAction(int $id): JsonResponse
    {
        /** @var Process $process */
        $process = $this->processRepository->find($id);

        return $this->json(['status' => $process?->getStatus()]);
    }
}
