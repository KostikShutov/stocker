<?php

declare(strict_types=1);

namespace App\Consumer;

use DateTime;
use Throwable;
use App\Entity\Process;
use App\Service\ProcessService;
use App\Message\ProcessMessage;
use App\Service\PredictionCreator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ProcessConsumer implements MessageHandlerInterface
{
    private ProcessService $processService;

    private PredictionCreator $predictionCreator;

    public function __construct(
        ProcessService $processService,
        PredictionCreator $predictionCreator
    ) {
        $this->processService = $processService;
        $this->predictionCreator = $predictionCreator;
    }

    public function __invoke(ProcessMessage $processMessage): void
    {
        $process = $this->processService->getProcess($processMessage->getId());

        if (!is_null($process) && $process->isStatusWaiting()) {
            $this->processService->changeProcessStatus($process, Process::STATUS_PROCESSING);

            try {
                $this->predictionCreator->create($process);
                $process->setSuccess(true);
            } catch (Throwable $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
                $process->setSuccess(false);
            }

            $process->setEndedAt(new DateTime());
            $this->processService->changeProcessStatus($process, Process::STATUS_DONE);
        }
    }
}
