<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Process;
use App\Repository\ProcessRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route(name="image_", path="/image/")
 */
final class ImageController extends AbstractController
{
    private ProcessRepository $processRepository;

    public function __construct(ProcessRepository $processRepository)
    {
        $this->processRepository = $processRepository;
    }

    /**
     * @Route(name="show", path="show/{id}", requirements={"id"="\d+"})
     */
    public function showAction(int $id): Response
    {
        /** @var Process $process */
        $process = $this->processRepository->find($id);

        if (is_null($process)) {
            throw $this->createNotFoundException();
        }

        return $this->render('image/show.html.twig', [
            'image' => $process->getImage()
        ]);
    }
}
