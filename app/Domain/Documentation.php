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

#[ORM\Entity(DatabaseCategoriesRepository::class), ORM\Table(name: 'docs')]
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
     * @param non-empty-string $title
     * @param string $content
     * @param Menu|null $menu
     * @param DocumentationId|null $id
     */
    public function __construct(
        Category $category,
        string $title,
        string $content = '',
        DocumentationId $id = null,
    ) {
        $this->category = $category;
        $this->title = $title;
        $this->content = Content::raw($content);
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
}
