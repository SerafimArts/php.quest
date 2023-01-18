<?php

declare(strict_types=1);

namespace App\Domain;

interface UrlProviderInterface
{
    /**
     * @return non-empty-string
     */
    public function getUrl(): string;
}
