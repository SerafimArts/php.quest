<?php

declare(strict_types=1);

namespace App\Infrastructure\Sync;

use Doctrine\ORM\EntityManagerInterface;

final class Pipeline
{
    public function __construct(
        private readonly string $url,
        private readonly string $directory,
        private readonly ArchiveDownloader $downloader,
        private readonly ArchiveExtractor $extractor,
        private readonly Synchronizer $synchronizer,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function process(): iterable
    {
        yield Status::DOWNLOAD => ($archive = $this->downloader->download($this->url));

        yield Status::EXTRACT => ($directory = $this->extractor->extract($archive));

        try {
            yield from $this->synchronizer->sync($directory, $this->directory);

            $this->em->flush();
        } finally {
            \unlink($archive);          // Delete archive
            $this->remove($directory);  // Delete downloaded files
        }
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
}
