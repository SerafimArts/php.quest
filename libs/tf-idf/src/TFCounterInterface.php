<?php

declare(strict_types=1);

namespace Local\TFIDF;

interface TFCounterInterface
{
    /**
     * @param string $text
     *
     * @return iterable<non-empty-string, int<1, max>>
     */
    public function compute(string $text): iterable;
}
