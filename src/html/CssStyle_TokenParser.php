<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace yii\twig\html;

class CssStyle_TokenParser extends BaseCss_TokenParser
{
    public function getNodeClass()
    {
        return '\yii\twig\html\StyleClassNode';
    }

    public function getTag()
    {
        return 'css_style';
    }
}
