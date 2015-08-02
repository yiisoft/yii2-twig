<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig;

/**
 * Yii-specific logic for Twig Environment
 */
class Environment extends \Twig_Environment
{

    /**
     * @var int umask for cache files
     */
    public $umask = null;

    /**
     * Applying custom umask to Twig cache files
     *
     * Idea of Paul Giberson: http://aknosis.com/2012/10/02/twig-cache-file-permissions/
     */
    protected function writeCacheFile($file, $content){
        if(!is_null($this->umask)) {
            $old = umask($this->umask);
        }

        parent::writeCacheFile($file, $content);

        if(isset($old)) {
            umask($old);
        }
    }
}
