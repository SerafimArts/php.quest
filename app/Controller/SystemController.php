<?php

declare(strict_types=1);

namespace App\Controller;

use App\Command\DocsUpdateCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SystemController
{
    public function __construct(
        private readonly string $secret,
        private readonly DocsUpdateCommand $command,
    ) {
    }

    #[Route(path: 'update', methods: ['POST'])]
    public function update(): Response
    {
        $input = new ArgvInput();
        $input->setInteractive(false);

        $output = new BufferedOutput();
        $output->setDecorated(false);

        $this->command->execute($input, $output);

        return new Response($output->fetch());
    }
}
