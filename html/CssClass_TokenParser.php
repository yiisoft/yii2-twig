<?php

namespace yii\twig\html;

class CssClass_TokenParser extends BaseCss_TokenParser
{
    public function getTag()
    {
        return 'css_class';
    }

    public function getNodeClass()
    {
        return CssClassNode::class;
    }
}