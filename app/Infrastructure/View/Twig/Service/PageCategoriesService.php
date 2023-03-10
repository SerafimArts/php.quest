<?php

declare(strict_types=1);

namespace App\Infrastructure\View\Twig\Service;

use App\Domain\Documentation\Category;
use App\Domain\Documentation\CategoriesRepositoryInterface;

final readonly class PageCategoriesService
{
    public function __construct(
        private CategoriesRepositoryInterface $categories,
    ) {
    }

    /**
     * @return iterable<Category>
     */
    public function all(): iterable
    {
        return $this->categories->findAll();
    }
}
