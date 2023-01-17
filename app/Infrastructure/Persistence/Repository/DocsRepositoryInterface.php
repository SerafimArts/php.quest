<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Category;
use App\Domain\Documentation;
use App\Domain\Shared\CategoryId;
use App\Domain\Shared\DocumentationId;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template-extends ObjectRepository<Documentation>
 */
interface DocsRepositoryInterface extends ObjectRepository
{
    /**
     * @param non-empty-string $filename
     *
     * @return Documentation|null
     */
    public function findByFilename(string $filename): ?Documentation;

    /**
     * @param DocumentationId $id
     *
     * @return Documentation|null
     */
    public function findById(DocumentationId $id): ?Documentation;
}
