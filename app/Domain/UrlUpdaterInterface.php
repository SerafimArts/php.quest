<?php

declare(strict_types=1);

namespace App\Domain;

use Psr\Http\Message\UriInterface;

interface UrlUpdaterInterface
{
    /**
     * @param non-empty-string|UriInterface|\Stringable $url
     *
     * @return void
     */
    public function setUrl(string|UriInterface|\Stringable $url): void;
}
