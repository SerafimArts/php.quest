<?php

declare(strict_types=1);

namespace App\Domain;

interface SluggableInterface extends RenamableInterface, NameableInterface
{
    /**
     * @return non-empty-string
     */
    public function getSlug(): string;

    /**
     * @param non-empty-string $slug
     *
     * @return void
     */
    public function setSlug(string $slug): void;
}
