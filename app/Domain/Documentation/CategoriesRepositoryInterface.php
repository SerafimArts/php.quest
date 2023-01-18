<?php

declare(strict_types=1);

namespace App\Domain\Documentation;

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
