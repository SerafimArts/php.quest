<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\Documentation;

use App\Domain\Documentation\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DatabaseCategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * {@inheritDoc}
     */
    public function findDefault(): ?Category
    {
        return $this->findOneBy([], [
            'priority' => 'DESC'
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(): iterable
    {
        return parent::findBy([], [
            'priority' => 'DESC',
        ]);
    }
}
