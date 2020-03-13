<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig;

use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;
use Twig\Source;

/**
 * Empty loader used for environment initialisation
 *
 * @author Andrzej Broński <andrzej1_1@o2.pl>
 */

class TwigEmptyLoader implements LoaderInterface
{
    /**
     * @inheritdoc
     */
    public function getSourceContext(string $name): Source
    {
        throw new LoaderError("Can not render using empty loader");
    }

    /**
     * @inheritdoc
     */
    public function getCacheKey(string $name): string
    {
        throw new LoaderError("Can not render using empty loader");
    }

    /**
     * @inheritdoc
     */
    public function isFresh(string $name, int $time): bool
    {
        throw new LoaderError("Can not render using empty loader");
    }

    /**
     * @inheritdoc
     */
    public function exists(string $name)
    {
        return false;
    }
}
