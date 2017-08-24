<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig;

/**
 * Template base class
 *
 * @author Alexei Tenitski <alexei@ten.net.nz>
 */
abstract class Template extends \Twig_Template
{
    /**
     * @inheritdoc
     */
    protected function getAttribute($object, $item, array $arguments = [], $type = \Twig_Template::ANY_CALL, $isDefinedTest = false, $ignoreStrictCheck = false)
    {
        // Twig uses isset() to check if attribute exists which does not work when attribute exists but is null
        if ($object instanceof \yii\base\Model) {
            if ($type === \Twig_Template::METHOD_CALL) {
                if ($this->env->hasExtension('sandbox')) {
                    $this->env->getExtension('sandbox')->checkMethodAllowed($object, $item);
                }
                return call_user_func_array([$object, $item], $arguments);
            } else {
                if ($this->env->hasExtension('sandbox')) {
                    $this->env->getExtension('sandbox')->checkPropertyAllowed($object, $item);
                }
                return $object->$item;
            }
        }

        return parent::getAttribute($object, $item, $arguments, $type, $isDefinedTest, $ignoreStrictCheck);
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
