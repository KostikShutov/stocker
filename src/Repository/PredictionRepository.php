<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Process;
use App\Entity\Prediction;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

final class PredictionRepository extends ServiceEntityRepository
{
    /**
     * {@inheritdoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prediction::class);
    }

    /**
     * @return Prediction[]
     */
    public function findByProcess(Process $process): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.process = :process')
            ->setParameter('process', $process)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
