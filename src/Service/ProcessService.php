<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Process;
use App\Repository\ProcessRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ProcessService
{
    private ProcessRepository $processRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ProcessRepository $processRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->processRepository = $processRepository;
        $this->entityManager = $entityManager;
    }

    public function getProcess(int $id): ?Process
    {
        return $this->processRepository->find($id);
    }

    public function changeProcessStatus(Process $process, string $status): void
    {
        $process->setStatus($status);
        $this->entityManager->flush();
    }
}
