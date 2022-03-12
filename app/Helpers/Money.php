<?php

namespace App\Helpers;

use NumberFormatter;

class Money
{
    public static function format($value)
    {
        $formatter = new NumberFormatter('en', NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($value, config('app.currency'));
    }
}