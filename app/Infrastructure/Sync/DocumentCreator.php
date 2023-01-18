<?php

declare(strict_types=1);

namespace App\Infrastructure\Sync;

use App\Domain\Documentation;
use App\Infrastructure\Persistence\Repository\CategoriesRepositoryInterface;
use Local\ContentRenderer\ResultInterface;
use Symfony\Component\Finder\SplFileInfo;

final class DocumentCreator
{
    public function __construct(
        private readonly CategoriesRepositoryInterface $categories,
    ) {
    }

    public function create(SplFileInfo $file, ResultInterface $result): Documentation
    {
        $category = $this->categories->findDefault() ?? throw new \LogicException(
            'Cannot find default Category for new document'
        );

        return new Documentation(
            category: $category,
            title: $this->getTitle($result),
            filename: $file->getRelativePathname(),
        );
    }

    /**
     * @param ResultInterface $result
     *
     * @return string
     */
    private function getTitle(ResultInterface $result): string
    {
        $level = 10;
        $title = 'New Document';

        foreach ($result->getHeadings() as $heading) {
            if ($heading->level < $level) {
                $level = $heading->level;
                $title = \trim($heading->text);
            }
        }

        return $title;
    }
}
