<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type\Documentation;

use App\Domain\Documentation\Page;
use App\Domain\Shared\Documentation\PageId;
use App\Infrastructure\GraphQL\Type\Relation;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

#[Type(name: 'Page')]
final class PageType
{
    public function __construct(
        #[Field(outputType: 'ID!')]
        public readonly PageId $id,
        #[Field]
        public readonly string $title,
        #[Field]
        public readonly string $url,
        #[Field]
        public readonly string $content,
        #[Field]
        public readonly bool $isAvailable,
        #[Field]
        public readonly ?\DateTimeInterface $updatedAt,
        #[Field(outputType: 'Category')]
        public readonly CategoryType $category,
    ) {
    }

    public static function fromPage(Page $page): self
    {
        $content = $page->getContent();

        return new self(
            id: $page->getId(),
            title: $page->getTitle(),
            url: $page->getUrl(),
            content: (string)$content,
            isAvailable: !$page->isDeleted(),
            updatedAt: $page->updatedAt(),
            category: CategoryType::fromCategory($page->getCategory()),
        );
    }

    public static function fromPageOrNull(?Page $page): ?self
    {
        if ($page === null) {
            return null;
        }

        return self::fromPage($page);
    }
}
