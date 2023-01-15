<?php

declare(strict_types=1);

namespace App\Domain\Documentation;

interface ContentInterface extends \Stringable
{
    /**
     * @return string
     */
    public function getSource(): string;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @param string $content
     *
     * @return void
     */
    public function update(string $content): void;
}
