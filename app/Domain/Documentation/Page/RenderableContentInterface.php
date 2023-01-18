<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Page;

use Local\ContentRenderer\ContentRendererInterface;
use Local\ContentRenderer\ResultInterface;

interface RenderableContentInterface extends ContentInterface
{
    /**
     * @param ContentRendererInterface $renderer
     *
     * @return void
     */
    public function render(ContentRendererInterface $renderer): void;

    /**
     * @param string $content
     * @param ContentRendererInterface|null $renderer
     *
     * @return void
     */
    public function update(string $content, ContentRendererInterface $renderer = null): void;

    /**
     * @param ResultInterface $result
     *
     * @return void
     */
    public function updateUsing(string $content, ResultInterface $result): void;

    /**
     * Cleaning up prerendered content.
     *
     * @return void
     */
    public function clear(): void;

    /**
     * Returns {@see true} if the content has been rendered and is available
     * for display, and {@see false} otherwise.
     *
     * @return bool
     */
    public function isRendered(): bool;
}
