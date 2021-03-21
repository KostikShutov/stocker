<?php

declare(strict_types=1);

namespace App\Repository;

use DateTimeInterface;
use App\Entity\Metal;
use App\Entity\Method;
use App\Entity\Period;
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
    public function findPredictionsGroupedByCreatedAt(): array
    {
        return $this->createQueryBuilder('p')
            ->groupBy('p.createdAt')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Prediction[]
     */
    public function findByFilter(Metal $metal, Method $method, Period $period, DateTimeInterface $createdAt): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.metal = :metal')
            ->andWhere('p.method = :method')
            ->andWhere('p.period = :period')
            ->andWhere('p.createdAt = :createdAt')
            ->setParameter('metal', $metal)
            ->setParameter('method', $method)
            ->setParameter('period', $period)
            ->setParameter('createdAt', $createdAt)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
