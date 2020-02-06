<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig\html;

use Twig\Node\Node;
use Twig\Compiler;

abstract class BaseClassNode extends Node
{
    public function compile(Compiler $compiler)
    {
        $name = $this->getAttribute('name')->getValue();
        $method = $this->getHelperMethod();

        $compiler
            ->addDebugInfo($this)
            ->write("\yii\helpers\Html::{$method}(\$context[\"{$name}\"],")
            ->subcompile($this->getNode('value'))
            ->raw(");\n");
    }

    abstract public function getHelperMethod();
}
