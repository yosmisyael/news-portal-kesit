<?php

use Carbon\Carbon;

if(!function_exists('parseTitleForControlPanelPage')) {
    function parseTitleForControlPanelPage(string $title): string
    {
        return explode(' | ', $title)[1];
    }
}

if(!function_exists('getCurrentDate')) {
    function getCurrentDate(): string
    {
        $now = Carbon::now();
        return $now->format('l, j M Y');
    }
}

if (!function_exists('parseTimeForHuman')) {
    function parseTimeForHuman(string $time): string
    {
        return Carbon::parse($time)->timezone('Asia/Jakarta')->diffForHumans();
    }
}
