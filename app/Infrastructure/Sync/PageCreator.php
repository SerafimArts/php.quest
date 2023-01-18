<?php

declare(strict_types=1);

namespace App\Infrastructure\Sync;

use App\Domain\Documentation\CategoriesRepositoryInterface;
use App\Domain\Documentation\Page;
use Local\ContentRenderer\ResultInterface;
use Symfony\Component\Finder\SplFileInfo;

final class PageCreator
{
    public function __construct(
        private readonly CategoriesRepositoryInterface $categories,
    ) {
    }

    public function create(SplFileInfo $file, ResultInterface $result): Page
    {
        $category = $this->categories->findDefault() ?? throw new \LogicException(
            'Cannot find default Category for new document'
        );

        return new Page(
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
