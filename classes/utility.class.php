<?php

class Utility {

    private static $host;
    private static $dir;

    public static function buildShortURLFromHash( $url_hash )
    {
        self::$host = $_SERVER['HTTP_HOST'];
        self::$dir = dirname($_SERVER['PHP_SELF']) == '/' ? '/' : dirname($_SERVER['PHP_SELF']) . '/';
        return $url_short = self::$host . self::$dir . $url_hash;
    }

    public static function redirect ( $url_long )
    {
        header( "Location: " . $url_long );
        exit;
    }

    public static function validateInput( $input )
    {
        if ( empty( $input ) ) // не ввели URL и нажали кнопку
        {
            return 'Введите URL<br>';
        }
        elseif ( !filter_var( $input, FILTER_VALIDATE_URL ) ) // ввели некорректный URL
        {
            return 'Invalid URL<br>';
        }
        else
        {
            return '';
        }
    }

}