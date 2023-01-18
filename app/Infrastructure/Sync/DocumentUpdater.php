<?php

declare(strict_types=1);

namespace App\Infrastructure\Sync;

use App\Domain\Documentation;
use App\Infrastructure\Persistence\Repository\DocsRepositoryInterface;
use Local\ContentRenderer\ContentRendererInterface;
use Symfony\Component\Finder\SplFileInfo;

final class DocumentUpdater
{
    public function __construct(
        private readonly ContentRendererInterface $renderer,
        private readonly DocumentCreator $creator,
        private readonly DocsRepositoryInterface $docs,
    ) {
    }

    public function update(SplFileInfo $file): Documentation
    {
        $source = $file->getContents();
        $result = $this->renderer->render($source);

        $page = $this->docs->findByFilename($file->getRelativePathname())
            ?? $this->creator->create($file, $result);

        $content = $page->getContent();

        if ($content instanceof Documentation\PrerenderedContentInterface) {
            $content->updateUsing($source, $result);
        }

        return $page;
    }
}
