<?php

declare(strict_types=1);

namespace App\Infrastructure\Sync;

final class ArchiveExtractor
{
    /**
     * @param non-empty-string $temp
     */
    public function __construct(
        private readonly string $temp,
    ) {
    }

    /**
     * @return non-empty-string
     * @throws \Exception
     */
    private function createOutputDirectory(): string
    {
        return $this->temp . '/' . \hash('xxh128', \random_bytes(32));
    }

    /**
     * @param non-empty-string $pathname
     *
     * @return non-empty-string
     * @throws \Exception
     */
    public function extract(string $pathname): string
    {
        $directory = $this->createOutputDirectory();
        $archive = new \ZipArchive();

        if (!$archive->open($pathname)) {
            throw new \LogicException('An error occurred while reading archive');
        }

        $archive->extractTo($directory);
        $archive->close();

        return $directory;
    }
}
