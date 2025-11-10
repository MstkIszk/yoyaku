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

function getImagePath(String $fileName="")
{
    $filePath = resource_path('images');

    return $filePath;
}

function ZipCodeFormat(string $zip): string
{
    // baseAddrZip: 数字7桁または3桁-4桁の形式から、強制的に 3桁-4桁 に整形
    $zipCode = str_replace('-', '', $zip);
    if (strlen($zipCode) === 7) {
        $formattedZipCode = substr($zipCode, 0, 3) . '-' . substr($zipCode, 3, 4);
    } else {
        // バリデーションが通っていればこのケースは発生しないはずだが、念のため元データを使用
        $formattedZipCode = $zip;
    }

    return $formattedZipCode;
}


/**
 * 日本の電話番号を固定電話の形式 (X-XXXX-XXXX または XX-XXXX-XXXX) に整形する。
 * 数字以外の文字を削除した後、桁数に基づいてハイフンを挿入する。
 *
 * @param string $tel
 * @return string
 */
function telNoFormat(string $tel): string
{
    $tel = preg_replace('/[^0-9]/', '', $tel); // 数字以外を削除
    $formattedTel = $tel; // デフォルトはハイフン除去後の数字列
    
    // 10桁または11桁の電話番号を想定
    if (strlen($tel) === 10) {
        // 例: 03-1234-5678, 09-1234-5678 (市外局番2桁, 3桁, 4桁を想定)
        if (preg_match('/^(\d{2})(\d{4})(\d{4})$/', $tel, $matches)) {
                $formattedTel = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
        } else {
            $formattedTel = $tel;
        }
    } elseif (strlen($tel) === 11) {
        // 携帯電話/050などの形式 (例: 090-1234-5678 の形式)
        if (preg_match('/^(\d{3})(\d{4})(\d{4})$/', $tel, $matches)) {
                $formattedTel = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
        } else {
                $formattedTel = $tel;
        }
    }
    
    return $formattedTel;
}

