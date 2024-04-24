<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace yii\twig;

/**
 * Helper to get Twig version information
 *
 * @author David Ferencz <david.ferencz@protonmail.com>
 */
class TwigVersionHelper
{
    /**
     * In twig 3.9 there were a few internal changes that this library was relying on.
     * Because these were internal implementation of twig, the authors could break it in a minor version.
     * 1. Since twig:3.9 the 'twig_get_attribute' function was renamed to CoreExtension::getAttribute.
     * 2. Twig does not hold the contents of the template in the output buffer anymore (introducing use_yield and reworked rendering)
     *
     * @return bool
     */
    public static function above39(): bool
    {
        return !function_exists('twig_get_attribute');
    }
}
