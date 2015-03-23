Twig Extension for Yii 2
========================

This extension provides a `ViewRender` that would allow you to use [Twig](http://twig.sensiolabs.org/) view template engine
with [Yii framework 2.0](http://www.yiiframework.com).

For license information check the [LICENSE](LICENSE.md)-file.

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii2-twig/v/stable.png)](https://packagist.org/packages/yiisoft/yii2-twig)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii2-twig/downloads.png)](https://packagist.org/packages/yiisoft/yii2-twig)
[![Build Status](https://travis-ci.org/yiisoft/yii2-twig.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-twig)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiisoft/yii2-twig
```

or add

```
"yiisoft/yii2-twig": "~2.0.0"
```

to the require section of your composer.json.

Usage
-----

To use this extension, simply add the following code in your application configuration:

```php
return [
    //....
    'components' => [
        'view' => [
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    // set cachePath to false in order to disable template caching
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    // ... see ViewRenderer for more options
                ],
            ],
        ],
    ],
];
```
