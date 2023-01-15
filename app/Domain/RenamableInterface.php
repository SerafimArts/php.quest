<?php

declare(strict_types=1);

namespace App\Domain;

interface RenamableInterface
{
    /**
     * @param non-empty-string $title
     *
     * @return void
     */
    public function rename(string $title): void;
}
