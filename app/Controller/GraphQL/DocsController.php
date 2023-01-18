<?php

declare(strict_types=1);

namespace App\Controller\GraphQL;

use App\Domain\Shared\Documentation\PageId;
use App\Infrastructure\GraphQL\Type\Documentation\PageType;
use App\Infrastructure\Persistence\Repository\Documentation\PageRepositoryInterface;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;

final class DocsController
{
    public function __construct(
        private readonly PageRepositoryInterface $docs,
    ) {
    }

    #[Query(name: 'page', outputType: 'Page')]
    public function findBy(string $url = null, string $id = null): ?PageType
    {
        $page = match (true) {
            \is_string($url) => $this->docs->findByUrl($url),
            \is_string($id) => $this->docs->findById(PageId::fromString($id)),
            default => throw new GraphQLException('URL or ID required'),
        };

        if ($page === null) {
            return null;
        }

        return new PageType(
            id: $page->getId(),
            title: $page->getTitle(),
        );
    }
}
