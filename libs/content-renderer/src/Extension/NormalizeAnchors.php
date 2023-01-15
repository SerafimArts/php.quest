<?php

declare(strict_types=1);

namespace Local\ContentRenderer\Extension;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Inline\Text;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class NormalizeAnchors extends Extension
{
    public function __construct(
        private SluggerInterface $slugger,
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
            if ($node instanceof Heading) {
                // Apply only for H2
                if ($node->getLevel() !== 2) {
                    continue;
                }

                $first = $node->firstChild();

                //
                // Replace <h2>Text</h2>
                // To <h2><a href="#text">Text</a></h2>
                //
                if ($first instanceof Text) {
                    $slug = $this->slugger->slug($first->getLiteral());
                    $lower = (string)$slug->lower();

                    $this->addAttribute($node, 'data-anchor', $lower);

                    $link = new Link('#' . $lower);
                    $link->appendChild($first);
                    $this->addAttribute($link, 'name', $lower);

                    $node->replaceChildren([$link]);
                }
            }
        }
    }
}
