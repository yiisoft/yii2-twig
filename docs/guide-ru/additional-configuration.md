Дополнительная конфигурация
===========================

Расширение Yii Twig позволяет вам определять собственный синтаксис и переносить стандартные классы-хелперы в шаблоны. 
Давайте рассмотрим параметры конфигурации.

## Globals

Вы можете добавлять хелперы или другие значения с помощью секции `globals` в конфигурации приложения. Вы можете
определить хелперы Yii или любые ваши переменные следующим образом:

```php
'globals' => [
    'html' => ['class' => '\yii\helpers\Html'],
    'name' => 'Carsten',
    'GridView' => ['class' => '\yii\grid\GridView'],
],
```

Сконфигурировав один раз, вы можете использовать globals в любых ваших шаблонах Twig:

```twig
Hello, {{name}}! {{ html.a('Please login', 'site/login') | raw }}.

{{ GridView.widget({'dataProvider' : provider}) | raw }}
```

## Функции

Вы можете определять дополнительные функции следующим образом:

```php
'functions' => [
    'rot13' => 'str_rot13',
    'truncate' => '\yii\helpers\StringHelper::truncate',
    new \Twig_SimpleFunction('rot14', 'str_rot13'),
    new \Twig_SimpleFunction('add_*', function ($symbols, $val) {
        return $val . $symbols;
    }, ['is_safe' => ['html']]),
    'callable_add_*' => function ($symbols, $val) {
        return $val . $symbols;
    },
    'sum' => function ($a, $b) {
        return $a + $b;
    }
],
```

И использовать эти функции в шаблоне Twig:

```twig
{{ rot13('test') }}
{{ truncate(post.text, 100) }}
{{ rot14('test') }}
{{ add_42('answer') }}
{{ callable_add_42('test') }}
{{ sum(1, 2) }}
```

## Фильтры

Дополнительные фильтры можно добавлять в конфигурации в секции `filters`:

```php
'filters' => [
    'jsonEncode' => '\yii\helpers\Json::htmlEncode',
    new \Twig_SimpleFilter('rot13', 'str_rot13'),
    new \Twig_SimpleFilter('add_*', function ($symbols, $val) {
        return $val . $symbols;
    }, ['is_safe' => ['html']]),
    'callable_rot13' => function($string) {
        return str_rot13($string);
    },
    'callable_add_*' => function ($symbols, $val) {
        return $val . $symbols;
    }
],
```

В шаблоне применение фильтров выглядит следующим образом:

```twig
{{ model|jsonEncode }}
{{ 'test'|rot13 }}
{{ 'answer'|add_42 }}
{{ 'test'|callable_rot13 }}
{{ 'answer'|callable_add_42 }}
```