<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\Documentation;

use App\Domain\Documentation\Page;
use App\Domain\Shared\Documentation\PageId;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template-extends ObjectRepository<Page>
 */
interface PageRepositoryInterface extends ObjectRepository
{
    /**
     * @param non-empty-string $filename
     *
     * @return Page|null
     */
    public function findByFilename(string $filename): ?Page;

    /**
     * @param non-empty-string $url
     *
     * @return Page|null
     */
    public function findByUrl(string $url): ?Page;

    /**
     * @param PageId $id
     *
     * @return Page|null
     */
    public function findById(PageId $id): ?Page;
}
