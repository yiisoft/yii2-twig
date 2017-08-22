<?php

namespace yii\twig\html;

class CssClass_TokenParser extends BaseCss_TokenParser
{
    public function getNodeClass()
    {
        return '\yii\twig\html\CssClassNode';
    }

    public function getTag()
    {
        return 'css_class';
    }
}
