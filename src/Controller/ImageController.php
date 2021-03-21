<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route(name="image_", path="/image/")
 */
final class ImageController extends AbstractController
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * @Route(name="show", path="show/{id}", requirements={"id"="\d+"})
     */
    public function showAction(int $id): Response
    {
        $image = $this->imageRepository->find($id);

        if (is_null($image)) {
            throw $this->createNotFoundException();
        }

        return $this->render('image/show.html.twig', [
            'image' => $image->getData()
        ]);
    }
}
