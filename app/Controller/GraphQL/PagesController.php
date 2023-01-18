<?php

declare(strict_types=1);

namespace App\Controller\GraphQL;

use TheCodingMachine\GraphQLite\Types\ID;
use App\Domain\Documentation\PagesRepositoryInterface;
use App\Domain\Shared\Documentation\PageId;
use App\Infrastructure\GraphQL\Type\Documentation\PageType;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;

final class PagesController
{
    public function __construct(
        private readonly PagesRepositoryInterface $pages,
    ) {
    }

    #[Query(name: 'pageByUrl', outputType: 'Page')]
    public function pageByUrl(string $url): ?PageType
    {
        return PageType::fromPageOrNull(
            $this->pages->findByUrl($url),
        );
    }

    #[Query(name: 'pageById', outputType: 'Page')]
    public function pageById(ID $id): ?PageType
    {
        try {
            return PageType::fromPageOrNull(
                $this->pages->findById(
                    PageId::fromString((string)$id->val()),
                ),
            );
        } catch (InvalidUuidStringException $e) {
            throw new GraphQLException($e->getMessage());
        }
    }
}
