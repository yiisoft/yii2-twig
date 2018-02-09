<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig;

/**
 * Template helper
 */
class Template
{
    /**
     * Returns the attribute value for a given array/object.
     *
     * @param \Twig_Environment $env
     * @param \Twig_Source $source
     * @param mixed $object The object or array from where to get the item
     * @param mixed $item The item to get from the array or object
     * @param array $arguments An array of arguments to pass if the item is an object method
     * @param string $type The type of attribute (@see Twig_Template constants)
     * @param bool $isDefinedTest Whether this is only a defined check
     * @param bool $ignoreStrictCheck Whether to ignore the strict attribute check or not
     *
     * @return mixed The attribute value, or a Boolean when $isDefinedTest is true, or null when the attribute is not set and $ignoreStrictCheck is true
     *
     * @throws \Twig_Error_Runtime if the attribute does not exist and Twig is running in strict mode and $isDefinedTest is false
     *
     * @internal
     */
    public static function attribute(\Twig_Environment $env, \Twig_Source $source, $object, $item, array $arguments = [], string $type = \Twig_Template::ANY_CALL, bool $isDefinedTest = false, bool $ignoreStrictCheck = false)
    {
        if ($object instanceof ElementInterface) {
            self::_includeElementInTemplateCaches($object);
        }
        if (
            $type !== \Twig_Template::METHOD_CALL &&
            ($object instanceof Object || $object instanceof \yii\base\Model) &&
            $object->canGetProperty($item)
        ) {
            return $isDefinedTest ? true : $object->$item;
        }
        // Convert any Twig_Markup arguments back to strings (unless the class *extends* Twig_Markup)
        foreach ($arguments as $key => $value) {
            if (is_object($value) && get_class($value) === \Twig_Markup::class) {
                $arguments[$key] = (string)$value;
            }
        }
        return \twig_get_attribute($env, $source, $object, $item, $arguments, $type, $isDefinedTest, $ignoreStrictCheck);
    }

    /**
     * @inheritdoc
     */
    protected function displayWithErrorHandling(array $context, array $blocks = array())
    {
        try {
            parent::displayWithErrorHandling($context, $blocks);
        } catch (\Error $e) {
            $r = new \ReflectionObject($this);
            $file = $r->getFileName();
            foreach ($e->getTrace() as $trace) {
                if (isset($trace['file']) and $trace['file'] == $file) {
                    $debugInfo = $this->getDebugInfo();
                    if (isset($trace['line']) && isset($debugInfo[$trace['line']])) {
                        throw new \Twig_Error_Runtime(sprintf('An exception has been thrown during the rendering of a template ("%s").', $e->getMessage()), $debugInfo[$trace['line']], $this->getSourceContext());
                    }
                }
            }

            throw $e;
        }
    }
}
