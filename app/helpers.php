<?php

if (! function_exists('formatWeight')) {
    function formatWeight(float|null $value): string
    {
        if ($value === null) return '-';

        $rounded = round($value, 2);

        if ($rounded == (int) $rounded) {
            return number_format((int) $rounded, 0, ',', '');
        }

        return number_format($rounded, 2, ',', '');
    }
}

if (! function_exists('formatCurrency')) {
    function formatCurrency(float|int|null $value): string
    {
        if ($value === null) return '-';

        return 'Rp'.number_format((float) $value, 0, ',', '.');
    }
}
