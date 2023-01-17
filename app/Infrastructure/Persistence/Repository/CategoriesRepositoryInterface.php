<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Category;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template-extends ObjectRepository<Category>
 */
interface CategoriesRepositoryInterface extends ObjectRepository
{
    /**
     * @return Category|null
     */
    public function findDefault(): ?Category;
}
