<?php

declare(strict_types=1);

namespace App\Service;

use Throwable;
use App\Entity\Metal;
use App\Entity\Method;
use App\Entity\Period;
use App\Service\Api\ApiProvider;

final class AccuracyChecker
{
    private InformationFinder $informationFinder;

    private MethodRequester $methodRequester;

    public function __construct(
        InformationFinder $informationFinder,
        MethodRequester $methodRequester
    ) {
        $this->informationFinder = $informationFinder;
        $this->methodRequester = $methodRequester;
    }

    /**
     * @throws Throwable
     */
    public function check(): void
    {
        /** @var Method[] $methods */
        $methods = $this->informationFinder->getInformation(Method::class);
        /** @var Metal $metal */
        $metal = $this->informationFinder->getInformationBySlug(Metal::class, Metal::METAL_GOLD);
        /** @var Period $period */
        $period = $this->informationFinder->getInformationBySlug(Period::class, Period::PERIOD_SHORT);

        foreach ($methods as $method) {
            $title = $method->getTitle();
            $time = time();
            echo sprintf('[%s] Start', $title) . PHP_EOL;

            try {
                $json = $this->methodRequester->request($method->getSlug(), [
                    'metal'    => $metal->getId(),
                    'provider' => ApiProvider::PROVIDER_YAHOO,
                    'period'   => $period->getDays(),
                    'evaluate' => 1
                ]);

                echo sprintf('[%s] End with %d seconds', $title, time() - $time) . PHP_EOL;
                echo sprintf('[%s] Loss: %f, Accuracy: %f', $title, $json['Loss'][0], $json['Acc'][0]);
            } catch (Throwable $e) {
                echo sprintf('[%s] End with exception', $title) . PHP_EOL;
                echo sprintf('[%s] Message %s', $title, $e->getMessage());
            }

            echo PHP_EOL . PHP_EOL;
        }
    }
}
