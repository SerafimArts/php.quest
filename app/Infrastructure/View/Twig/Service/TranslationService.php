<?php

declare(strict_types=1);

namespace App\Infrastructure\View\Twig\Service;

use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class TranslationService
{
    public function __construct(
        private TranslatorInterface $i18n,
    ) {
    }

    public function current(): string
    {
        return $this->i18n->getLocale();
    }
}
