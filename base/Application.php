<?php
/**
 * Created by PhpStorm.
 * User: maks
 * Date: 09.08.18
 * Time: 15:05
 */

namespace app\base;


class Application
{
    static $config;
    static $mode;
    static $components;

    public static function initApplication(array $config)
    {
        self::$config = $config;
        self::$mode = $config['mode'];
        self::$components = $config['components'];
    }

    protected function camelToSnake($camelString)
    {
        $re = '/(?=[A-Z])/';
        $stringParts = preg_split($re, $camelString);
        $kebabString = implode('_', $stringParts);
        $kebabString = strtolower($kebabString);

        return $kebabString;
    }

    protected function arrayCamelToSnake($camelArray)
    {
        $kebabArray = [];

        foreach ($camelArray as $camelString) {
            $kebabArray[] = $this->camelToSnake($camelString);
        }

        return $kebabArray;
    }
}
