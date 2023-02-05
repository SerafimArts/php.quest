<?php

declare(strict_types=1);

namespace Local\TFIDF;

interface IDFCounterInterface
{
    /**
     * @param string $text
     * @param iterable<string> $texts
     * @param int<1, max> $count
     *
     * @return iterable<non-empty-string, int<1, max>>
     */
    public function compare(string $text, iterable $texts, int $count = 10): iterable;
}
