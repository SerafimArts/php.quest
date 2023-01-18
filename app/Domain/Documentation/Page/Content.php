<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Page;

use Doctrine\ORM\Mapping as ORM;
use Local\ContentRenderer\ContentRendererInterface;
use Local\ContentRenderer\ResultInterface;

#[ORM\Embeddable]
class Content implements RenderableContentInterface
{
    /**
     * @var string
     */
    #[ORM\Column(name: 'source', type: 'text', nullable: false)]
    protected string $source = '';

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'rendered', type: 'text', nullable: true)]
    protected ?string $rendered = null;

    /**
     * @param string $source
     * @param string|null $rendered
     */
    public function __construct(string $source, string $rendered = null)
    {
        $this->source = $source;
        $this->rendered = $rendered;
    }

    /**
     * @return static
     */
    public static function empty(): self
    {
        return new self('');
    }

    /**
     * @param string $content
     *
     * @return static
     */
    public static function raw(string $content): self
    {
        return new self($content);
    }

    /**
     * @param string $content
     * @param ContentRendererInterface $renderer
     *
     * @return static
     */
    public static function from(string $content, ContentRendererInterface $renderer): self
    {
        return new self($content, (string)$renderer->render($content));
    }

    /**
     * {@inheritDoc}
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return \trim($this->source) === '';
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): void
    {
        $this->rendered = null;
    }

    /**
     * {@inheritDoc}
     */
    public function render(ContentRendererInterface $renderer): void
    {
        $this->rendered = (string)$renderer->render($this->source);
    }

    /**
     * {@inheritDoc}
     */
    public function update(string $content, ContentRendererInterface $renderer = null): void
    {
        if ($renderer === null) {
            $this->source = $content;
            $this->clear();

            return;
        }

        $this->updateUsing($content, $renderer->render($content));
    }

    /**
     * {@inheritDoc}
     */
    public function updateUsing(string $content, ResultInterface $result): void
    {
        $this->source = $content;
        $this->rendered = $result->getContents();
    }

    /**
     * {@inheritDoc}
     */
    public function isRendered(): bool
    {
        return $this->rendered !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->rendered ?: $this->source;
    }
}
