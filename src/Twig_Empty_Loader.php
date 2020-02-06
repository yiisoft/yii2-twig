<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig;

use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;

/**
 * Empty loader used for environment initialisation
 *
 * @author Andrzej Broński <andrzej1_1@o2.pl>
 */

class Twig_Empty_Loader implements LoaderInterface
{
    /**
     * @inheritdoc
     */
    public function getSourceContext($name)
    {
        throw new LoaderError("Can not render using empty loader");
    }

    /**
     * @inheritdoc
     */
    public function getCacheKey($name)
    {
        throw new LoaderError("Can not render using empty loader");
    }

    /**
     * @inheritdoc
     */
    public function isFresh($name, $time)
    {
        throw new LoaderError("Can not render using empty loader");
    }

    /**
     * @inheritdoc
     */
    public function exists($name)
    {
        return false;
    }
}
