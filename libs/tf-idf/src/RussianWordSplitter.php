<?php

declare(strict_types=1);

namespace Local\TFIDF;

final class RussianWordSplitter extends WordSplitter
{
    /**
     * @param string $char
     *
     * @return bool
     */
    protected function isWordChar(string $char): bool
    {
        return \ctype_alpha($char)
            || $char === '-'
            || $char === 'ё' || $char === 'Ё'
            || ($char >= 'а' && $char <= 'я')
            || ($char >= 'А' && $char <= 'Я');
    }
}
