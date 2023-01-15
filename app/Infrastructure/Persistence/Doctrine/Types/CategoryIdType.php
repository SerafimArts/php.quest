<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Types;

use App\Domain\Shared\CategoryId;

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
