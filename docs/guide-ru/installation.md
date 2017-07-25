Установка
============

Установка состоит из двух частей: получение пакета расширения через Composer и конфигурирование приложения.

## Установка расширения

Предпочтительный способ установки расширения через [composer](http://getcomposer.org/download/).

Для этого запустите команду

```
php composer.phar require --prefer-dist yiisoft/yii2-twig
```

или добавьте

```
"yiisoft/yii2-twig": "~2.0.0"
```

в секцию require вашего composer.json.

## Конфигурирование приложения

Чтобы использовать шаблонизатор Twig, вам необходимо сконфигурировать компонент `view` следующим образом:

```php
[
    'components' => [
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => [
                        'html' => ['class' => '\yii\helpers\Html'],
                    ],
                    'uses' => ['yii\bootstrap'],
                ],
                // ...
            ],
        ],
    ],
]
```

После этого вы можете создавать шаблоны в файлах с расширением `.twig` (или использовать другое расширение файла, 
предварительно переконфигурировав компонент). В отличие от стандартных файлов вида, при использовании шаблонизатора 
Twig вы должны указывать расширение в вызове метода контроллера `$this->render()`:

```php
return $this->render('renderer.twig', ['username' => 'Alex']);
```