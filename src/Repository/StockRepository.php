<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Stock;
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
        $result = $this->createQueryBuilder('known_quote')
            ->where('known_quote.metal = :metal')
            ->andWhere('known_quote.openPrice = :openPrice')
            ->andWhere('known_quote.highPrice = :highPrice')
            ->andWhere('known_quote.lowPrice = :lowPrice')
            ->andWhere('known_quote.closePrice = :closePrice')
            ->andWhere('known_quote.date = :date')
            ->andWhere('known_quote.provider = :provider')
            ->setParameter('metal', $knownQuote->getMetal())
            ->setParameter('openPrice', $knownQuote->getOpenPrice())
            ->setParameter('highPrice', $knownQuote->getHighPrice())
            ->setParameter('lowPrice', $knownQuote->getLowPrice())
            ->setParameter('closePrice', $knownQuote->getClosePrice())
            ->setParameter('date', $knownQuote->getDate())
            ->setParameter('provider', $knownQuote->getProvider())
            ->setMaxResults(1)
            ->orderBy('known_quote.id', 'ASC')
            ->getQuery()
            ->getResult();

        return empty($result) ? null : reset($result);
    }
}
