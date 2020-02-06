<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig\html;

use Twig\Extension\AbstractExtension;

class HtmlHelperExtension extends AbstractExtension
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
            'html' => ['class' => '\yii\helpers\Html'],
        ];
    }
}
