<?php

declare(strict_types=1);

namespace App\Service;

use Throwable;
use DateTimeInterface;
use App\Entity\Metal;
use App\Entity\Method;
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
    public function check(DateTimeInterface $start = null, DateTimeInterface $end = null): void
    {
        /** @var Method[] $methods */
        $methods = $this->informationFinder->getInformation(Method::class);
        /** @var int $metal */
        $metal = $this->informationFinder->getInformationBySlug(Metal::class, Metal::METAL_GOLD)->getId();

        foreach ($methods as $method) {
            $title = $method->getTitle();
            $slug = $method->getSlug();
            $time = time();
            echo sprintf('[%s] Start %s', $title, $slug) . PHP_EOL;

            try {
                $json = $this->methodRequester->request($slug, [
                    'metal'    => $metal,
                    'provider' => ApiProvider::PROVIDER_YAHOO,
                    'start'    => $start?->format('Y-m-d'),
                    'end'      => $end?->format('Y-m-d'),
                    'period'   => 0,
                    'evaluate' => 1
                ]);

                echo sprintf('[%s] End with %d seconds', $title, time() - $time) . PHP_EOL;
                echo sprintf('[%s] Loss: %f, Accuracy: %f', $title, $json['Loss'][0], $json['Acc'][0]);
            } catch (Throwable $e) {
                echo sprintf('[%s] End with exception', $title) . PHP_EOL;
                echo sprintf('[%s] Message: %s', $title, $e->getMessage());
            }

            echo PHP_EOL . PHP_EOL;
        }
    }
}
