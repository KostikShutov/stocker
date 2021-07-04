<?php

declare(strict_types=1);

namespace App\Service;

use Throwable;
use DateTimeInterface;
use App\Entity\Metal;
use App\Entity\Method;
use App\Service\Api\ApiProvider;
use App\Repository\StockRepository;

final class AccuracyChecker
{
    private InformationFinder $informationFinder;

    private MethodRequester $methodRequester;

    private StockRepository $stockRepository;

    public function __construct(
        InformationFinder $informationFinder,
        MethodRequester $methodRequester,
        StockRepository $stockRepository
    ) {
        $this->informationFinder = $informationFinder;
        $this->methodRequester = $methodRequester;
        $this->stockRepository = $stockRepository;
    }

    /**
     * @throws Throwable
     */
    public function check(
        DateTimeInterface $start = null,
        DateTimeInterface $end = null,
        string $method = null
    ): void {
        /** @var Method[] $methods */
        if (empty($method)) {
            $methods = $this->informationFinder->getInformation(Method::class);
        } else {
            $method = $this->informationFinder->getInformationBySlug(Method::class, $method);
            $methods = $method instanceof Method ? [$method] : [];
        }

        if (empty($methods)) {
            return;
        }

        /** @var Metal $metal */
        $metal = $this->informationFinder->getInformationBySlug(Metal::class, Metal::METAL_GOLD);

        foreach ($methods as $method) {
            $title = $method->getTitle();
            $slug = $method->getSlug();
            $time = time();
            echo sprintf('[%s] Start %s', $title, $slug) . PHP_EOL;

            try {
                $json = $this->methodRequester->request($slug, [
                    'metal'    => $metal->getId(),
                    'provider' => ApiProvider::PROVIDER_YAHOO,
                    'start'    => $start?->format('Y-m-d'),
                    'end'      => $end?->format('Y-m-d'),
                    'period'   => 0,
                    'evaluate' => 1
                ]);

                $count = $this->stockRepository->findCount($metal, $start, $end);

                echo sprintf('[%s] End with %d seconds', $title, time() - $time) . PHP_EOL;
                echo sprintf('[%s] Elements %d, %s', $title, $count, $json[0]['Data']);
            } catch (Throwable $e) {
                echo sprintf('[%s] End with exception', $title) . PHP_EOL;
                echo sprintf('[%s] Message: %s', $title, $e->getMessage());
            }

            echo PHP_EOL . PHP_EOL;
        }
    }
}
