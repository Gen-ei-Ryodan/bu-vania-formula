<?php

if (! function_exists('formatWeight')) {
    function formatWeight(float|null $value, int $gramDec = 3): string
    {
        if ($value === null) return '-';

        return $value >= 1
            ? number_format($value, 0)
            : number_format($value, $gramDec);
    }
}
