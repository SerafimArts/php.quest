<?php

declare(strict_types=1);

namespace App\Domain\Documentation;

use App\Domain\Documentation\Page\ContentInterface;

interface ContentProviderInterface
{
    /**
     * @return ContentInterface
     */
    public function getContent(): ContentInterface;
}
