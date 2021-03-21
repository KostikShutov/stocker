<?php

declare(strict_types=1);

namespace App\Command;

use DateTime;
use InvalidArgumentException;
use App\Service\Api\ProviderResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReceiveQuotesCommand extends Command
{
    private ProviderResolver $providerResolver;

    public function __construct(ProviderResolver $providerResolver)
    {
        parent::__construct();
        $this->providerResolver = $providerResolver;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:stocks:download')
            ->setDescription('Получить котировки за определенный период и записать их в базу данных')
            ->addOption('provider', 'p', InputOption::VALUE_REQUIRED, 'Api provider id: yahoo, stooq')
            ->addOption('start', null, InputOption::VALUE_REQUIRED, 'Start date, d.m.Y')
            ->addOption('end', null, InputOption::VALUE_REQUIRED, 'End date, d.m.Y');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $provider = $this->providerResolver->getProvider((string) $input->getOption('provider'));

        if (is_null($provider)) {
            throw new InvalidArgumentException('Unknown api provider id');
        }

        $start = $this->getDateFromString((string) $input->getOption('start'));
        $end = $this->getDateFromString((string) $input->getOption('end'));

        if (is_null($start) || is_null($end)) {
            throw new InvalidArgumentException('Invalid format of start or end date, correct format is d.m.Y');
        }

        if ($start > $end) {
            throw new InvalidArgumentException('End date more than start date');
        }

        $count = $provider->provide($start, $end);

        $output->writeln(sprintf('%d known quotes was created', $count));

        return 0;
    }

    private function getDateFromString(string $date): ?DateTime
    {
        $date = date_create_from_format('d.m.Y', $date);

        return $date instanceof DateTime ? $date->setTime(0, 0) : null;
    }
}
