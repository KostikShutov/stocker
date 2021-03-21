<?php

declare(strict_types=1);

namespace App\Consumer;

use DateTime;
use Exception;
use App\Entity\Metal;
use App\Entity\Method;
use App\Entity\Period;
use App\Entity\Process;
use App\Service\ProcessService;
use App\Message\ProcessMessage;
use App\Service\InformationFinder;
use App\Service\JsonPredictionParser;
use Symfony\Component\Process\Process as Command;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ProcessConsumer implements MessageHandlerInterface
{
    private ProcessService $processService;

    private InformationFinder $informationFinder;

    private JsonPredictionParser $jsonPredictionParser;

    public function __construct(
        ProcessService $processService,
        InformationFinder $informationFinder,
        JsonPredictionParser $jsonPredictionParser
    ) {
        $this->processService = $processService;
        $this->informationFinder = $informationFinder;
        $this->jsonPredictionParser = $jsonPredictionParser;
    }

    public function __invoke(ProcessMessage $processMessage): void
    {
        $process = $this->processService->getProcess($processMessage->getId());

        if (!is_null($process) && $process->isStatusWaiting()) {
            $this->processService->changeProcessStatus($process, Process::STATUS_PROCESSING);

            /** @var Period|null $period */
            $period = $this->informationFinder->getInformationBySlug(Period::class, $process->getPeriod());
            /** @var Metal|null $metal */
            $metal = $this->informationFinder->getInformationBySlug(Metal::class, $process->getMetal());
            /** @var Method|null $method */
            $method = $this->informationFinder->getInformationBySlug(Method::class, $process->getMethod());
            $options = $process->getOptions();
            $options['period'] = $period->getDays();
            $options['metal'] = $metal->getId();
            unset($options['method']);
            $command = new Command(array_merge(
                [
                    'python',
                    sprintf('python/%s_main.py', $method->getSlug()),
                ],
                $this->parseOptions($options)
            ));

            $command->setTimeout(null);

            try {
                $command->mustRun();
                $this->jsonPredictionParser->parse($period, $metal, $method, $command->getOutput());
                $process->setSuccess(true);
            } catch (Exception $exception) {
                echo $exception->getMessage();
                $process->setSuccess(false);
            }

            $process->setEndedAt(new DateTime());
            $this->processService->changeProcessStatus($process, Process::STATUS_DONE);
        }
    }

    private function parseOptions(array $options): array
    {
        $result = [];

        foreach ($options as $key => $value) {
            if (!is_null($value)) {
                $result[] = '--' . $key;
                $result[] = $value;
            }
        }

        return $result;
    }
}
