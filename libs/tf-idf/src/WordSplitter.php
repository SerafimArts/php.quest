<?php

declare(strict_types=1);

namespace Local\TFIDF;

abstract class WordSplitter
{
    /**
     * @param string $text
     *
     * @return iterable<string>
     */
    public function split(string $text): iterable
    {
        $length = \mb_strlen($text);
        $buffer = '';

        for ($i = 0; $i < $length; ++$i) {
            $char = \mb_substr($text, $i, 1);

            if (!$this->isWordChar($char)) {
                if (\trim($buffer, '-') !== '') {
                    yield \trim($buffer, '-');
                }

                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        if (\trim($buffer, '-') !== '') {
            yield \trim($buffer, '-');
        }
    }

    /**
     * @param string $char
     *
     * @return bool
     */
    abstract protected function isWordChar(string $char): bool;
}
