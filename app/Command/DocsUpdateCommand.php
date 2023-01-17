<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Documentation;
use App\Infrastructure\Persistence\Repository\CategoriesRepositoryInterface;
use App\Infrastructure\Persistence\Repository\DocsRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Local\ContentRenderer\ContentRendererInterface;
use Local\ContentRenderer\ResultInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

#[AsCommand(name: 'docs:update')]
class DocsUpdateCommand extends Command
{
    private const REPOSITORY = 'https://github.com/SerafimArts/php.quest-docs/archive/refs/heads/master.zip';
    private const DIRECTORY = 'php.quest-docs-master';

    public function __construct(
        private readonly string $temp,
        private readonly string $public,
        private readonly HttpClientInterface $client,
        private readonly EntityManagerInterface $em,
        private readonly DocsRepositoryInterface $docs,
        private readonly ClockInterface $clock,
        private readonly CategoriesRepositoryInterface $categories,
        private readonly ContentRendererInterface $renderer,
    ) {
        parent::__construct();
    }

    private function info(OutputInterface $output, string $message, int $step): void
    {
        $output->write(\sprintf(' - [%d/4] <comment>%s…</comment>', $step, $message));
    }

    private function completed(OutputInterface $output, int $step): void
    {
        $output->write("\r" . \str_repeat(' ', 80));
        $output->writeln(\sprintf("\r   [<info>%d/4</info>] <info>✓ Completed!</info>", $step));
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('GET', self::REPOSITORY);

        // ---------------------------------------------------------------------
        $this->info($output, 'Downloading archive', 1);
        $archive = $this->download($response, $this->temp);
        $this->completed($output, 1);


        // ---------------------------------------------------------------------
        $this->info($output, 'Extracting archive', 2);
        $directory = $this->extract($archive, $this->temp);
        $this->completed($output, 2);


        try {
            // -----------------------------------------------------------------
            $this->info($output, 'Processing data', 3);

            $this->deleteAll();

            $progress = new ProgressBar($output);
            foreach ($this->read($directory) as $file) {
                $progress->advance();

                if ($file->getExtension() === 'md') {
                    $this->process($file);
                    continue;
                }

                $this->copy($file);
            }
            $progress->clear();

            $this->em->flush();

            $this->completed($output, 3);
        } finally {
            // -----------------------------------------------------------------
            $this->info($output, 'Cleanup', 4);

            \unlink($archive);          // Delete archive
            $this->remove($directory);  // Delete downloaded files

            $this->completed($output, 4);
        }

        return self::SUCCESS;
    }

    private function copy(SplFileInfo $file): void
    {
        $name = \str_replace(self::DIRECTORY . '/', '', $file->getRelativePathname());
        $pathname = $this->public . '/' . $name;

        if (!\is_dir(\dirname($pathname))) {
            \mkdir(\dirname($pathname), recursive: true);
        }

        \copy($file->getRealPath(), $pathname);
    }

    private function deleteAll(): void
    {
        foreach ($this->docs->findAll() as $page) {
            $page->delete($this->clock);

            $this->em->persist($page);
        }
    }

    private function process(SplFileInfo $file): void
    {
        $result = $this->renderer->render(
            $source = $file->getContents()
        );

        $page = $this->docs->findByFilename($file->getRelativePathname())
            ?? $this->create($file, $result);

        // Update content
        $content = $page->getContent();
        if ($content instanceof Documentation\PrerenderedContentInterface) {
            $content->updateUsing($source, $result);
        }

        $page->restore();

        $this->em->persist($page);
    }

    private function create(SplFileInfo $file, ResultInterface $result): Documentation
    {
        $category = $this->categories->findDefault()
            ?? throw new \LogicException('Cannot find default category for new document');

        return new Documentation(
            category: $category,
            title: $this->getTitle($result),
            content: '',
            filename: $file->getRelativePathname(),
        );
    }

    /**
     * @param ResultInterface $result
     *
     * @return string
     */
    private function getTitle(ResultInterface $result): string
    {
        $level = 10;
        $title = 'New Document';

        foreach ($result->getHeadings() as $heading) {
            if ($heading->level < $level) {
                $level = $heading->level;
                $title = \trim($heading->text);
            }
        }

        return $title;
    }

    /**
     * @param non-empty-string $directory
     *
     * @return iterable<SplFileInfo>
     */
    private function read(string $directory): iterable
    {
        return (new Finder())
            ->files()
            ->in($directory);
    }

    /**
     * @param non-empty-string $pathname
     * @param non-empty-string $directory
     *
     * @return non-empty-string
     * @throws \Exception
     */
    private function extract(string $pathname, string $directory): string
    {
        $directory .= '/' . $this->randomName();

        $archive = new \ZipArchive();

        if (!$archive->open($pathname)) {
            throw new \LogicException('An error occurred while reading archive');
        }

        $archive->extractTo($directory);
        $archive->close();

        return $directory;
    }

    /**
     * @param string $prefix
     * @param string $suffix
     *
     * @return non-empty-string
     */
    private function randomName(string $prefix = '', string $suffix = ''): string
    {
        return $prefix . \hash('xxh128', \random_bytes(32)) . $suffix;
    }

    /**
     * @param non-empty-string $directory
     *
     * @return void
     */
    private function remove(string $directory): void
    {
        $files = new \RecursiveIteratorIterator(
            iterator: new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            mode: \RecursiveIteratorIterator::CHILD_FIRST,
        );

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            if ($file->isDir()) {
                \rmdir($file->getRealPath());
                continue;
            }

            \unlink($file->getRealPath());
        }

        \rmdir($directory);
    }

    /**
     * @param ResponseInterface $response
     * @param non-empty-string $directory
     *
     * @return non-empty-string
     * @throws TransportExceptionInterface
     */
    private function download(ResponseInterface $response, string $directory): string
    {
        $pathname = $directory . '/' . $this->randomName(suffix: '.zip');

        $fp = \fopen($pathname, 'w+');
        \flock($fp, \LOCK_EX);

        foreach ($this->client->stream($response) as $chunk) {
            \fwrite($fp, $chunk->getContent());
        }

        \flock($fp, \LOCK_UN);
        \fclose($fp);

        return $pathname;
    }
}
