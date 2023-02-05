<?php

declare(strict_types=1);

namespace Local\TFIDF;

final class IDFCounter implements IDFCounterInterface
{
    public function __construct(
        private readonly TFCounterInterface $tf = new TFCounter(),
    ) {
    }

    /**
     * @param string $text
     * @param iterable<string> $texts
     * @param int<1, max> $count
     *
     * @return iterable<string, int<1, max>>
     */
    public function compare(string $text, iterable $texts, int $count = 10): iterable
    {
        $base = [...$this->tf->compute($text)];

        foreach ($texts as $comparison) {
            foreach ($this->tf->compute($comparison) as $word => $weight) {
                if (isset($base[$word])) {
                    $base[$word] /= $weight;
                }
            }
        }

        \arsort($base);

        return \array_slice($base, 0, $count);
    }
}
