<?php

declare(strict_types=1);

namespace Local\TFIDF;

use voku\helper\UTF8;

final class TFCounter implements TFCounterInterface
{
    public function __construct(
        private readonly WordSplitter $splitter = new RussianWordSplitter(),
    ) {
    }

    public function compute(string $text): iterable
    {
        $words = [];

        foreach ($this->splitter->split($text) as $word) {
            $lower = UTF8::strtolower($word);

            if (!isset($words[$lower])) {
                $words[$lower] = 1;
            } else {
                $words[$lower]++;
            }
        }

        \arsort($words);

        return $words;
    }
}
