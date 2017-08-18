<?php

namespace yii\twig\html;

class StyleClassNode extends BaseClassNode
{
    public function __construct(\Twig_Token $name, $value, \Twig_Token $operator, $lineno = 0, $tag = null)
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
                throw new \Twig_Error("Operator {$operator} no found;");
        }
    }
}
