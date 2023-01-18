<?php

declare(strict_types=1);

namespace App\Infrastructure\Sync;

use App\Domain\Documentation\Page;
use App\Domain\Documentation\PagesRepositoryInterface;
use Local\ContentRenderer\ContentRendererInterface;
use Symfony\Component\Finder\SplFileInfo;

final class PageUpdater
{
    public function __construct(
        private readonly ContentRendererInterface $renderer,
        private readonly PageCreator $creator,
        private readonly PagesRepositoryInterface $docs,
    ) {
    }

    public function update(SplFileInfo $file): Page
    {
        $source = $file->getContents();
        $result = $this->renderer->render($source);

        $page = $this->docs->findByFilename($file->getRelativePathname())
            ?? $this->creator->create($file, $result);

        $content = $page->getContent();

        if ($content instanceof Page\RenderableContentInterface) {
            $content->updateUsing($source, $result);
        }

        return $page;
    }
}
