<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig;

use Twig\Extension\ProfilerExtension;
use Twig\Profiler\Dumper\TextDumper;
use \Twig\Profiler\Profile as TwigProfile;
use yii\web\View;

class Profile extends ProfilerExtension
{
    protected $view;
    protected $profiler;


    public function __construct(TwigProfile $profile)
    {
        $profile = new TwigProfile();
        $dumper = new TextDumper();
        parent::__construct($profile);
        $view = \Yii::$app->getView();
        $view->on(View::EVENT_AFTER_RENDER, function () use ($profile, $dumper) {
            \Yii::trace($dumper->dump($profile));
        });
    }
}
