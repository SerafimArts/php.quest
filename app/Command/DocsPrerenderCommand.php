<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Documentation\Page\RenderableContentInterface;
use App\Domain\Documentation\PagesRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Local\ContentRenderer\ContentRendererInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'docs:prerender')]
final class DocsPrerenderCommand extends Command
{
    public function __construct(
        private readonly ContentRendererInterface $renderer,
        private readonly PagesRepositoryInterface $docs,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->docs->findAll() as $page) {
            $content = $page->getContent();

            if ($content instanceof RenderableContentInterface) {
                $content->render($this->renderer);
            }

            $this->em->persist($page);
        }

        $this->em->flush();

        return self::SUCCESS;
    }
}
