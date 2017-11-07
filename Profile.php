<?php

namespace yii\twig;

use Twig_Profiler_Profile;
use yii\web\View;

class Profile extends \Twig_Extension_Profiler
{
    protected $view;

    protected $profiler;

    public function __construct(Twig_Profiler_Profile $profile)
    {
        $profile = new \Twig_Profiler_Profile();
        $dumper = new \Twig_Profiler_Dumper_Text();
        parent::__construct($profile);
        $view = \Yii::$app->getView();
        $view->on(View::EVENT_AFTER_RENDER, function () use ($profile, $dumper) {
            \Yii::trace($dumper->dump($profile));
        });
    }
}