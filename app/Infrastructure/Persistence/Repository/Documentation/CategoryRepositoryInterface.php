<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\Documentation;

use App\Domain\Documentation\Category;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template-extends ObjectRepository<Category>
 */
interface CategoryRepositoryInterface extends ObjectRepository
{
    /**
     * @return Category|null
     */
    public function findDefault(): ?Category;
}
