<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type\Documentation;

use App\Domain\Documentation\Category;
use App\Domain\Shared\Documentation\CategoryId;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

#[Type(name: 'Category')]
final class CategoryType
{
    public function __construct(
        #[Field(outputType: 'ID!')]
        public readonly CategoryId $id,
        #[Field]
        public readonly string $title,
    ) {
    }

    public static function fromCategory(Category $category): self
    {
        return new self(
            id: $category->getId(),
            title: $category->getTitle(),
        );
    }
}
