<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use App\Entity\Stock;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;

final class StockPersister
{
    private EntityManagerInterface $entityManager;

    private StockRepository $stockRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        StockRepository $stockRepository
    ) {
        $this->entityManager = $entityManager;
        $this->stockRepository = $stockRepository;
    }

    /**
     * @param Stock[] $knownQuotes
     *
     * @throws Exception
     */
    public function persist(array $knownQuotes): int
    {
        $count = 0;

        if (!empty($knownQuotes)) {
            $this->entityManager->clear(Stock::class);
            $this->entityManager->beginTransaction();

            try {
                foreach ($knownQuotes as $knownQuote) {
                    if (is_null($this->stockRepository->findDuplicate($knownQuote))) {
                        $this->entityManager->persist($knownQuote);
                        $count++;
                    }
                }

                $this->entityManager->flush();
                $this->entityManager->commit();
            } catch (Exception $e) {
                $this->entityManager->rollback();
                throw $e;
            }
        }

        return $count;
    }
}
