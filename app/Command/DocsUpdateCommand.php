<?php

declare(strict_types=1);

namespace App\Command;

use App\Infrastructure\Sync\Pipeline;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'docs:update')]
class DocsUpdateCommand extends Command
{
    public function __construct(
        private readonly Pipeline $pipeline,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->pipeline->process() as $status => $info) {
            $output->writeln(' - ' . \ucfirst($status->name) . ': ' . $info);
        }

        return self::SUCCESS;
    }
}
