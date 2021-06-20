<?php

declare(strict_types=1);

namespace App\Command;

use Throwable;
use App\Service\AccuracyChecker;
use Symfony\Component\Console\Command\Command;
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
            ->setDescription('Проверка точности нейронных сетей');
    }

    /**
     * {@inheritdoc}
     *
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('default_socket_timeout', '120');

        $this->accuracyChecker->check();

        return 0;
    }
}
