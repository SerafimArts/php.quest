<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Types\Documentation;

use App\Domain\Shared\Documentation\CategoryId;
use App\Infrastructure\Persistence\Doctrine\Types\UuidType;

final class CategoryIdType extends UuidType
{
    /**
     * {@inheritDoc}
     */
    protected static function getClass(): string
    {
        return CategoryId::class;
    }
}
