<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig\html;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use yii\twig\ViewRendererStaticClassProxy;

class HtmlHelperExtension extends AbstractExtension implements GlobalsInterface
{
    public function getTokenParsers()
    {
        return [
            new CssClass_TokenParser(),
            new CssStyle_TokenParser()
        ];
    }

    public function getGlobals(): array
    {
        return [
            'html' => new ViewRendererStaticClassProxy('\yii\helpers\Html'),
        ];
    }
}
