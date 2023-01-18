<?php

declare(strict_types=1);

namespace App\Domain;

use Psr\Http\Message\UriInterface;

interface UrlProviderInterface extends NameUpdaterInterface, NameProviderInterface
{
    /**
     * @return non-empty-string
     */
    public function getUrl(): string;

    /**
     * @param non-empty-string|UriInterface|\Stringable $url
     *
     * @return void
     */
    public function setUrl(string|UriInterface|\Stringable $url): void;
}
