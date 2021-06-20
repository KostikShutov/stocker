<?php

declare(strict_types=1);

namespace App\Command;

use InvalidArgumentException;
use App\Service\StockDownloader;
use App\Service\DateParserHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class StocksDownloadCommand extends Command
{
    private StockDownloader $stockDownloader;

    public function __construct(StockDownloader $stockDownloader)
    {
        parent::__construct();
        $this->stockDownloader = $stockDownloader;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:stocks:download')
            ->setAliases(['st:download'])
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
        $provider = (string) $input->getOption('provider');
        $start = DateParserHelper::getDateFromFormat((string) $input->getOption('start'));
        $end = DateParserHelper::getDateFromFormat((string) $input->getOption('end'));

        if (is_null($start) || is_null($end)) {
            throw new InvalidArgumentException('Invalid format of start or end date, correct format is d.m.Y');
        }

        $count = $this->stockDownloader->download($provider, $start, $end);

        $output->writeln(sprintf('%d stocks was created', $count));

        return 0;
    }
}
