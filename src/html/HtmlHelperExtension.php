<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig\html;

use yii\twig\ViewRendererStaticClassProxy;

class HtmlHelperExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    public function getTokenParsers()
    {
        return [
            new CssClass_TokenParser(),
            new CssStyle_TokenParser()
        ];
    }

    public function getGlobals()
    {
        return [
            'html' => new ViewRendererStaticClassProxy('\yii\helpers\Html'),
        ];
    }
}
