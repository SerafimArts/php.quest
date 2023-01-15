<?php

declare(strict_types=1);

namespace Local\ContentRenderer\Renderer;

use Local\ContentRenderer\Extension\ImportHeaderClasses;
use Local\ContentRenderer\Extension\NormalizeAnchors;
use Local\ContentRenderer\Extension\QuotesFormatter;
use Local\ContentRenderer\Extension\RemoveEmptyParagraphs;
use Local\ContentRenderer\Extension\RemoveStyleTags;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Util\HtmlFilter;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;
use Spatie\CommonMarkHighlighter\IndentedCodeRenderer;
use Symfony\Component\String\Slugger\SluggerInterface;

class DocumentationRenderer extends Renderer
{
    public function __construct(
        SluggerInterface $slugger,
    ) {
        parent::__construct(['html_input' => HtmlFilter::ALLOW]);

        $this->env->addExtension(new QuotesFormatter());
        $this->env->addExtension(new RemoveEmptyParagraphs());
        $this->env->addExtension(new NormalizeAnchors($slugger));
        $this->env->addExtension(new RemoveStyleTags());

        $this->env->addRenderer(FencedCode::class, new FencedCodeRenderer(['php', 'html']));
        $this->env->addRenderer(IndentedCode::class, new IndentedCodeRenderer(['php', 'html']));
    }
}
