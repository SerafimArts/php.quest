<?php

declare(strict_types=1);

namespace Local\ContentRenderer\Extension;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;
use League\CommonMark\Node\Inline\AbstractStringContainer;

final readonly class QuotesFormatter extends Extension
{
    public function __construct(
        private string $prefix = 'quote-',
    ) {
    }

    /**
     * @param EnvironmentBuilderInterface $environment
     * @return void
     */
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addEventListener(DocumentParsedEvent::class, $this, -50);
    }

    /**
     * @param DocumentParsedEvent $e
     * @return void
     */
    public function __invoke(DocumentParsedEvent $e): void
    {
        $document = $e->getDocument();

        foreach ($document->iterator() as $node) {
            if ($node instanceof BlockQuote) {
                $this->modifyQuote($node);
            }
        }
    }

    /**
     * @param BlockQuote $quote
     *
     * @return AbstractStringContainer|null
     */
    private function getStyle(BlockQuote $quote): ?AbstractStringContainer
    {
        foreach ($quote->children() as $paragraph) {
            $content = $paragraph->firstChild();

            if ($content instanceof Strong) {
                $child = $content->firstChild();

                if ($child instanceof AbstractStringContainer) {
                    return $child;
                }
            }
        }

        return null;
    }

    /**
     * @param BlockQuote $quote
     * @return void
     */
    private function modifyQuote(BlockQuote $quote): void
    {
        $style = $this->getStyle($quote);

        if ($style !== null) {
            $this->addClass($quote, $this->prefix . \strtolower($style->getLiteral()));
        }
    }
}
