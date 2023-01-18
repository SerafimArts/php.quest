<?php

declare(strict_types=1);

namespace App\Infrastructure\Sync;

use App\Infrastructure\Persistence\Repository\Documentation\PageRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class Synchronizer
{
    public function __construct(
        private readonly string $public,
        private readonly ClockInterface $clock,
        private readonly EntityManagerInterface $em,
        private readonly PageRepositoryInterface $docs,
        private readonly PageUpdater $updater,
    ) {
    }

    /**
     * @param non-empty-string $directory
     * @param non-empty-string $prefix
     *
     * @return iterable<Status, SplFileInfo>
     */
    public function sync(string $directory, string $prefix): iterable
    {
        $this->deleteAll();

        foreach ($this->read($directory) as $file) {
            if (\strtolower($file->getExtension()) === 'md') {
                $document = $this->updater->update($file);
                $document->restore();

                $this->em->persist($document);

                yield Status::UPDATE => $file;

                continue;
            }

            $this->copy($file, $prefix);

            yield Status::COPY => $file;
        }
    }

    private function deleteAll(): void
    {
        foreach ($this->docs->findAll() as $page) {
            $page->delete($this->clock);

            $this->em->persist($page);
        }
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
     * @param SplFileInfo $file
     * @param non-empty-string $prefix
     *
     * @return void
     */
    private function copy(SplFileInfo $file, string $prefix): void
    {
        $name = \str_replace($prefix . '/', '', $file->getRelativePathname());
        $pathname = $this->public . '/' . $name;

        if (!\is_dir(\dirname($pathname))) {
            \mkdir(\dirname($pathname), recursive: true);
        }

        \copy($file->getRealPath(), $pathname);
    }
}
