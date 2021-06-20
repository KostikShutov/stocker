<?php

declare(strict_types=1);

namespace App\Command;

use DateTime;
use Throwable;
use App\Service\AccuracyChecker;
use App\Service\DateParserHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AccuracyCheckCommand extends Command
{
    private AccuracyChecker $accuracyChecker;

    public function __construct(AccuracyChecker $accuracyChecker)
    {
        parent::__construct();
        $this->accuracyChecker = $accuracyChecker;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:accuracy:check')
            ->setAliases(['acc:check'])
            ->setDescription('Проверка точности нейронных сетей')
            ->addOption('start', null, InputOption::VALUE_REQUIRED, 'Start date, d.m.Y')
            ->addOption('end', null, InputOption::VALUE_REQUIRED, 'End date, d.m.Y');
    }

    /**
     * {@inheritdoc}
     *
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('default_socket_timeout', '300');

        $start = DateParserHelper::getDateFromFormat((string) $input->getOption('start'));
        $end = DateParserHelper::getDateFromFormat((string) $input->getOption('end'));

        $this->accuracyChecker->check($start, $end);

        return 0;
    }
}
