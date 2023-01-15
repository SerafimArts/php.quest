<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DatabaseCategoriesRepository extends ServiceEntityRepository implements CategoriesRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAll(): iterable
    {
        return parent::findBy([], [
            'priority' => 'DESC',
        ]);
    }
}
