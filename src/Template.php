<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig;

use Twig\Source;
use Twig\Environment;
use Twig\Error\RuntimeError;
use Twig\Template as TwigTemplate;
use Twig\Markup;

/**
 * Template helper
 */
class Template
{
    /**
     * Returns the attribute value for a given array/object.
     *
     * @param Environment $env
     * @param Source $source
     * @param mixed $object The object or array from where to get the item
     * @param mixed $item The item to get from the array or object
     * @param array $arguments An array of arguments to pass if the item is an object method
     * @param string $type The type of attribute (@see Twig_Template constants)
     * @param bool $isDefinedTest Whether this is only a defined check
     * @param bool $ignoreStrictCheck Whether to ignore the strict attribute check or not
     *
     * @return mixed The attribute value, or a Boolean when $isDefinedTest is true, or null when the attribute is not set and $ignoreStrictCheck is true
     *
     * @throws RuntimeError if the attribute does not exist and Twig is running in strict mode and $isDefinedTest is false
     *
     * @internal
     */
    public static function attribute(Environment $env, Source $source, $object, $item, array $arguments = [], string $type = TwigTemplate::ANY_CALL, bool $isDefinedTest = false, bool $ignoreStrictCheck = false)
    {
        if (
            $type !== TwigTemplate::METHOD_CALL &&
            ($object instanceof Object || $object instanceof \yii\base\Model) &&
            $object->canGetProperty($item)
        ) {
            return $isDefinedTest ? true : $object->$item;
        }
        // Convert any Twig_Markup arguments back to strings (unless the class *extends* Twig_Markup)
        foreach ($arguments as $key => $value) {
            if (is_object($value) && get_class($value) === Markup::class) {
                $arguments[$key] = (string)$value;
            }
        }
        return \twig_get_attribute($env, $source, $object, $item, $arguments, $type, $isDefinedTest, $ignoreStrictCheck);
    }
}
