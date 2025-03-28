<?php

namespace App\Core\Helpers\Lang;

class LanguageHelper
{
    /**
     * @return string
     */
    public static function getTitle(): string
    {
        return 'title_' . app()->getLocale();
    }

    /**
     * @return string
     */
    public static function getShortTitle(): string
    {
        return 'short_title_' . app()->getLocale();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'name_' . app()->getLocale();
    }

    public static function getFileName(): string
    {
        return 'file_' . app()->getLocale();
    }


    public static function getDesc(): string
    {
        return 'desc_' . app()->getLocale();
    }

    public static function getProperty(string $property): string
    {
        return $property . '_' . app()->getLocale();
    }
}
