<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Documentation\PagesRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Local\TFIDF\IDFCounterInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'docs:extract-keywords')]
class DocsExtractKeywordsCommand extends Command
{
    /**
     * @param EntityManagerInterface $em
     * @param PagesRepositoryInterface $pages
     */
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PagesRepositoryInterface $pages,
        private readonly IDFCounterInterface $idf,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $texts = [];
        $pages = $this->pages->findAll();

        foreach ($pages as $page) {
            $content = $page->getContent();
            $texts[(string)$page->getId()] = $content->getSource();
        }

        $progress = new ProgressBar($output, \count($pages));
        $progress->start();

        foreach ($pages as $page) {
            $needle = $texts[(string)$page->getId()];

            $haystack = $texts;
            unset($haystack[(string)$page->getId()]);

            $page->keywords = \array_keys(
                $this->idf->compare($needle, $haystack)
            );

            $progress->advance();
            $progress->display();

            $this->em->persist($page);
        }

        $progress->clear();

        $this->em->flush();

        return self::SUCCESS;
    }
}
