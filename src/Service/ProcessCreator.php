<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Process;
use App\Form\ConfigureMethod;
use App\Message\ProcessMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProcessCreator
{
    private EntityManagerInterface $entityManager;

    private MessageBusInterface $messageBus;

    public function __construct(
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus
    ) {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    public function create(ConfigureMethod $data): int
    {
        $process = (new Process())
            ->setMetal($data->getMetal())
            ->setMethod($data->getMethod())
            ->setPeriod($data->getPeriod())
            ->setStatus(Process::STATUS_WAITING)
            ->setOptions([
                'start'    => $data->getStart()?->format('Y-m-d'),
                'end'      => $data->getEnd()?->format('Y-m-d'),
                'provider' => $data->getProvider()
            ]);

        $this->entityManager->persist($process);
        $this->entityManager->flush();

        $id = $process->getId();

        $this->messageBus->dispatch(new ProcessMessage($id));

        return $id;
    }
}
