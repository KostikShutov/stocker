<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Metal;
use App\Entity\Method;
use App\Entity\Period;
use App\Entity\Process;
use App\Entity\Information;
use App\Service\InformationFinder;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

final class NameExtension extends AbstractExtension
{
    private InformationFinder $informationFinder;

    public function __construct(InformationFinder $informationFinder)
    {
        $this->informationFinder = $informationFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('name_method', [$this, 'nameMethod']),
            new TwigFilter('name_metal', [$this, 'nameMetal']),
            new TwigFilter('name_period', [$this, 'namePeriod']),
            new TwigFilter('name_status', [$this, 'nameStatus']),
        ];
    }

    public function nameMethod(string $slug): string
    {
        return $this->getTitleByInformation(
            $this->informationFinder->getInformationBySlug(Method::class, $slug)
        );
    }

    public function nameMetal(string $slug): string
    {
        return $this->getTitleByInformation(
            $this->informationFinder->getInformationBySlug(Metal::class, $slug)
        );
    }

    public function namePeriod(string $slug): string
    {
        return $this->getTitleByInformation(
            $this->informationFinder->getInformationBySlug(Period::class, $slug)
        );
    }

    public function nameStatus(string $status): string
    {
        return match ($status) {
            Process::STATUS_WAITING    => 'В ожидании',
            Process::STATUS_PROCESSING => 'В процессе',
            Process::STATUS_DONE       => 'Завершено',
            default                    => ''
        };
    }

    private function getTitleByInformation(?Information $information): string
    {
        return is_null($information) ? '' : $information->getTitle();
    }
}
