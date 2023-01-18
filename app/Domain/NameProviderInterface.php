<?php

declare(strict_types=1);

namespace App\Domain;

interface NameProviderInterface
{
    /**
     * @return non-empty-string
     */
    public function getTitle(): string;
}
