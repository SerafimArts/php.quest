<?php

declare(strict_types=1);

namespace App\Domain\Documentation;

use App\Domain\Creatable;
use App\Domain\CreatedDateProviderInterface;
use App\Domain\Documentation\Page\Content;
use App\Domain\Documentation\Page\ContentInterface;
use App\Domain\NameProviderInterface;
use App\Domain\NameUpdaterInterface;
use App\Domain\Shared\Documentation\PageId;
use App\Domain\Shared\EntityInterface;
use App\Domain\Updatable;
use App\Domain\UpdatedDateProviderInterface;
use App\Domain\UrlProviderInterface;
use App\Domain\UrlUpdaterInterface;
use App\Infrastructure\Persistence\Doctrine\Generator\UuidGenerator;
use App\Infrastructure\Persistence\Repository\Documentation\DatabaseCategoriesRepository;
use Doctrine\ORM\Mapping as ORM;
use Psr\Clock\ClockInterface;
use Psr\Http\Message\UriInterface;

#[ORM\Entity(DatabaseCategoriesRepository::class), ORM\Table(name: 'docs')]
class Page implements
    EntityInterface,
    NameProviderInterface,
    NameUpdaterInterface,
    UrlProviderInterface,
    UrlUpdaterInterface,
    ContentProviderInterface,
    CreatedDateProviderInterface,
    UpdatedDateProviderInterface
{
    use Creatable;
    use Updatable;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: PageId::class)]
    private PageId $id;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string')]
    private string $title;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: 'string')]
    private string $url = '';

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
     * @param PageId|null $id
     */
    public function __construct(
        Category $category,
        string $title,
        string|ContentInterface $content = '',
        string $filename = '',
        PageId $id = null,
    ) {
        $this->category = $category;
        $this->title = $title;
        $this->filename = $filename;
        $this->content = $content instanceof ContentInterface ? $content : Content::raw($content);
        $this->id = $id ?? PageId::fromNamespace(static::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): PageId
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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * {@inheritDoc}
     */
    public function setUrl(string|UriInterface|\Stringable $url): void
    {
        $this->url = match (true) {
            \is_string($url) => $url,
            $url instanceof UriInterface => \trim($url->getPath(), '/'),
            default => (string)$url,
        };
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
