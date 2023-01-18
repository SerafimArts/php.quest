<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Types\Documentation;

use App\Domain\Shared\Documentation\PageId;
use App\Infrastructure\Persistence\Doctrine\Types\UuidType;

final class PageIdType extends UuidType
{
    /**
     * {@inheritDoc}
     */
    protected static function getClass(): string
    {
        return PageId::class;
    }
}
