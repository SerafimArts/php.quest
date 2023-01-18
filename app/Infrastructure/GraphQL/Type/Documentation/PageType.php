<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type\Documentation;

use App\Domain\Shared\Documentation\PageId;
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
    ) {
    }
}
