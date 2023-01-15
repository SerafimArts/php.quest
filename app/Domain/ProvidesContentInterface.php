<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Documentation\ContentInterface;

interface ProvidesContentInterface
{
    /**
     * @return ContentInterface
     */
    public function getContent(): ContentInterface;
}
