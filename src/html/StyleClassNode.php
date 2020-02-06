<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig\html;

use Twig\Token;
use Twig\Error\Error;

class StyleClassNode extends BaseClassNode
{
    public function __construct(Token $name, $value, Token $operator, $lineno = 0, $tag = null)
    {
        parent::__construct(array('value' => $value), array('name' => $name, 'operator' => $operator), $lineno, $tag);
    }

    public function getHelperMethod()
    {
        $operator = $this->getAttribute('operator')->getValue();
        switch ($operator) {
            case '+':
                return 'addCssStyle';
            case '-':
                return 'removeCssStyle';
            default:
                throw new Error("Operator {$operator} no found;");
        }
    }
}
