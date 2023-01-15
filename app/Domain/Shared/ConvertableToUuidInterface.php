<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use Ramsey\Uuid\UuidInterface;

interface ConvertableToUuidInterface
{
    /**
     * @return UuidInterface
     */
    public function toUuid(): UuidInterface;
}
