<?php

declare(strict_types=1);

namespace Local\ContentRenderer;

interface ContentRendererInterface
{
    /**
     * @param string $content
     *
     * @return ResultInterface
     */
    public function render(string $content): ResultInterface;
}
