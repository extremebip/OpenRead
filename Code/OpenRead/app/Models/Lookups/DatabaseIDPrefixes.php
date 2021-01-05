<?php

namespace App\Models\Lookups;

class DatabaseIDPrefixes
{
    public const STORY = 'ST';
    public const GENRE = 'GR';
    public const CHAPTER = 'CH';
    public const COMMENT = 'CM';

    public static function GetPrefixByTableName($table)
    {
        $prefix = '';
        switch ($table) {
            case 'stories':
                $prefix = DatabaseIDPrefixes::STORY;
                break;
            case 'genres':
                $prefix = DatabaseIDPrefixes::GENRE;
                break;
            case 'chapters':
                $prefix = DatabaseIDPrefixes::CHAPTER;
                break;
            case 'comments':
                $prefix = DatabaseIDPrefixes::COMMENT;
                break;
        }
        return $prefix;
    }
}
