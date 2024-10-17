<?php
namespace App\Service;

class StringUtilities{

    public static function removeStartEndWhiteSpaces(string $string): string
    {
        $string = ltrim($string, " ");
        return rtrim($string, " ");
    }
}