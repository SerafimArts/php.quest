<?php

declare(strict_types=1);

namespace App\Domain\Documentation;

use App\Domain\NameProviderInterface;
use App\Domain\Shared\Documentation\CategoryId;
use App\Domain\Shared\EntityInterface;
use App\Infrastructure\Persistence\Doctrine\Generator\UuidGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'categories')]
class Category implements
    EntityInterface,
    NameProviderInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: CategoryId::class)]
    private CategoryId $id;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string')]
    private string $title;

    /**
     * @var int<0, max>
     */
    #[ORM\Column(type: 'smallint', options: ['unsigned' => true])]
    private int $priority;

    /**
     * @var Collection<Page>
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Page::class, fetch: 'EAGER')]
    #[ORM\OrderBy(['priority' => 'DESC'])]
    private Collection $pages;

    /**
     * @param non-empty-string $title
     * @param int<0, max> $priority
     * @param CategoryId|null $id
     */
    public function __construct(
        string $title,
        int $priority = 0,
        CategoryId $id = null,
    ) {
        $this->id = $id ?? CategoryId::fromNamespace(static::class);
        $this->title = $title;
        $this->priority = $priority;
        $this->pages = new ArrayCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): CategoryId
    {
        return $this->id;
    }

    /**
     * @return iterable<Page>
     */
    public function getPages(): iterable
    {
        return $this->pages;
    }

    /**
     * @return int<0, max>
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return non-empty-string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
