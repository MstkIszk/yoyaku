<?php

use App\Helpers\DayFrame;
use App\Patient;
use App\Section;
use Carbon\CarbonImmutable;

$WEEK_DAYS = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];

define('WEEK_DAYS', $WEEK_DAYS);

function calendar_culc(String $baseYM,int $numMonth)
{
    // DateTimeオブジェクトに変換
    $date = new DateTime($baseYM . '-01'); // 月初日を指定

    // 1ヶ月前に移動
    $date->modify("$numMonth month");
    return $date->format('Y-m');
}
