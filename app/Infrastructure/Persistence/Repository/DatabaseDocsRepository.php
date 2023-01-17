<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Documentation;
use App\Domain\Shared\DocumentationId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DatabaseDocsRepository extends ServiceEntityRepository implements DocsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Documentation::class);
    }

    /**
     * @param non-empty-string $filename
     *
     * @return Documentation|null
     */
    public function findByFilename(string $filename): ?Documentation
    {
        return $this->findOneBy(['filename' => $filename]);
    }

    /**
     * {@inheritDoc}
     */
    public function findById(DocumentationId $id): ?Documentation
    {
        return $this->find($id);
    }
}
