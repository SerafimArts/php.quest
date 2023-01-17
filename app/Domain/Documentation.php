<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Documentation\Content;
use App\Domain\Documentation\ContentInterface;
use App\Domain\Shared\DocumentationId;
use App\Domain\Shared\EntityInterface;
use App\Infrastructure\Persistence\Doctrine\Generator\UuidGenerator;
use App\Infrastructure\Persistence\Repository\DatabaseCategoriesRepository;
use Doctrine\ORM\Mapping as ORM;
use Psr\Clock\ClockInterface;

#[ORM\Entity(DatabaseCategoriesRepository::class), ORM\Table(name: 'docs', uniqueConstraints: [
    new ORM\UniqueConstraint('file_idx', ['filename'])
])]
class Documentation implements
    EntityInterface,
    SluggableInterface,
    ProvidesContentInterface,
    CreatableInterface,
    UpdatableInterface
{
    use Creatable;
    use Updatable;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: DocumentationId::class)]
    private DocumentationId $id;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string')]
    private string $title;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string')]
    private string $slug = '';

    /**
     * @var Category
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'pages')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private Category $category;

    /**
     * @var int<0, max>
     */
    #[ORM\Column(type: 'smallint', options: ['unsigned' => true, 'default' => 0])]
    private int $priority = 0;

    /**
     * @var ContentInterface
     */
    #[ORM\Embedded(class: Content::class, columnPrefix: 'content_')]
    private ContentInterface $content;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', options: ['default' => ''])]
    private string $filename = '';

    /**
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(name: 'deleted_at', type: 'datetimetz_immutable', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * @param Category $category
     * @param non-empty-string $title
     * @param string $content
     * @param DocumentationId|null $id
     */
    public function __construct(
        Category $category,
        string $title,
        string|ContentInterface $content = '',
        string $filename = '',
        DocumentationId $id = null,
    ) {
        $this->category = $category;
        $this->title = $title;
        $this->filename = $filename;
        $this->content = $content instanceof ContentInterface ? $content : Content::raw($content);
        $this->id = $id ?? DocumentationId::fromNamespace(static::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): DocumentationId
    {
        return $this->id;
    }

    /**
     * @return ContentInterface
     */
    public function getContent(): ContentInterface
    {
        return $this->content;
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * {@inheritDoc}
     */
    public function rename(string $title): void
    {
        $this->title = $title;
    }

    /**
     * {@inheritDoc}
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * {@inheritDoc}
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function deletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    /**
     * @return void
     */
    public function restore(): void
    {
        $this->deletedAt = null;
    }

    /**
     * @param \DateTimeInterface|ClockInterface $at
     *
     * @return void
     */
    public function delete(\DateTimeInterface|ClockInterface $at): void
    {
        if ($at instanceof ClockInterface) {
            $at = $at->now();
        }

        if ($at instanceof \DateTime) {
            $at = \DateTimeImmutable::createFromMutable($at);
        }

        $this->deletedAt = clone $at;
    }
}
