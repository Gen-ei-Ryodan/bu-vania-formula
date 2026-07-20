<?php

if (! function_exists('formatWeight')) {
    function formatWeight(float|null $value): string
    {
        if ($value === null) return '-';

        return number_format($value, 3);
    }
}
