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
     * {@inheritDoc}
     */
    public function findById(DocumentationId $id): ?Documentation
    {
        return $this->find($id);
    }
}
