<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Types;

use App\Domain\Shared\DocumentationId;

final class DocumentationIdType extends UuidType
{
    /**
     * {@inheritDoc}
     */
    protected static function getClass(): string
    {
        return DocumentationId::class;
    }
}
