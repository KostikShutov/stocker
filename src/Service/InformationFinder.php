<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Metal;
use App\Entity\Method;
use App\Entity\Period;
use App\Entity\Information;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class InformationFinder
{
    const CLASSES = [Metal::class, Method::class, Period::class];

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Information[]
     */
    public function getInformation(string $class): array
    {
        if ($this->validateClass($class)) {
            return $this->getRepository($class)->findAll();
        }

        return [];
    }

    public function getInformationBySlug(string $class, string $slug): ?Information
    {
        if ($this->validateClass($class)) {
            return $this->getRepository($class)->findOneBy([
                'slug' => $slug
            ]);
        }

        return null;
    }

    private function validateClass(string $class): bool
    {
        return in_array($class, self::CLASSES);
    }

    private function getRepository(string $class): ObjectRepository
    {
        return $this->entityManager->getRepository($class);
    }
}
