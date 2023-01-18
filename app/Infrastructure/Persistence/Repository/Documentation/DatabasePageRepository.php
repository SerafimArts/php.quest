<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\Documentation;

use App\Domain\Documentation\Page;
use App\Domain\Shared\Documentation\PageId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DatabasePageRepository extends ServiceEntityRepository implements PageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * {@inheritDoc}
     */
    public function findByFilename(string $filename): ?Page
    {
        return $this->findOneBy(['filename' => $filename]);
    }

    /**
     * {@inheritDoc}
     */
    public function findByUrl(string $url): ?Page
    {
        return $this->findOneBy(['slug' => \strtolower($url)]);
    }

    /**
     * {@inheritDoc}
     */
    public function findById(PageId $id): ?Page
    {
        return $this->find($id);
    }
}
