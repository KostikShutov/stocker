<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Metal;
use App\Entity\Stock;
use Doctrine\ORM\UnexpectedResultException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

final class StockRepository extends ServiceEntityRepository
{
    /**
     * {@inheritdoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    public function findDuplicate(Stock $knownQuote): ?Stock
    {
        /** @var Stock[] $result */
        $result = $this->createQueryBuilder('stock')
            ->where('stock.metal = :metal')
            ->andWhere('stock.openPrice = :openPrice')
            ->andWhere('stock.highPrice = :highPrice')
            ->andWhere('stock.lowPrice = :lowPrice')
            ->andWhere('stock.closePrice = :closePrice')
            ->andWhere('stock.date = :date')
            ->andWhere('stock.provider = :provider')
            ->setParameter('metal', $knownQuote->getMetal())
            ->setParameter('openPrice', $knownQuote->getOpenPrice())
            ->setParameter('highPrice', $knownQuote->getHighPrice())
            ->setParameter('lowPrice', $knownQuote->getLowPrice())
            ->setParameter('closePrice', $knownQuote->getClosePrice())
            ->setParameter('date', $knownQuote->getDate())
            ->setParameter('provider', $knownQuote->getProvider())
            ->setMaxResults(1)
            ->orderBy('stock.id', 'ASC')
            ->getQuery()
            ->getResult();

        return empty($result) ? null : reset($result);
    }

    public function findLast(Metal $metal, string $provider): ?Stock
    {
        try {
            return $this->createQueryBuilder('stock')
                ->where('stock.metal = :metal')
                ->andWhere('stock.provider = :provider')
                ->setParameter('metal', $metal)
                ->setParameter('provider', $provider)
                ->setMaxResults(1)
                ->orderBy('stock.date', 'DESC')
                ->getQuery()
                ->getSingleResult();
        } catch (UnexpectedResultException) {
            return null;
        }
    }
}
