<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 02.06.2017
 * Time: 8:11
 */

namespace yiiunit\extensions\twig\data;


class StaticAndConsts
{
    const FIRST_CONST = 'I am a const!';

    public static $staticVar = 'I am a static var!';

    public static function sticFunction($var)
    {
        return "I am a static function with param ${var}!";
    }
}