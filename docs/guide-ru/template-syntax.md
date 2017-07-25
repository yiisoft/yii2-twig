Синтаксис шаблонизатора
=======================

Лучшим ресурсом для изучения основ шаблонизатора Twig является официальная документация, которую можно найти тут - 
[twig.sensiolabs.org](http://twig.sensiolabs.org/documentation). Ниже описаны синтаксические расширения, специфичные для Yii:

## Вызов метода и функции

Если необходимо получить результат в переменную, вы можете вызвать метод или функцию следующим образом:

```twig
{% set result = my_function({'a' : 'b'}) %}
{% set result = myObject.my_function({'a' : 'b'}) %}
```

Если необходимо вывести результат без сохранения в переменную:

```twig
{{ my_function({'a' : 'b'}) }}
{{ myObject.my_function({'a' : 'b'}) }}
```

Также вы можете использовать обертку `void`, если нет необходимости сохранять результат:

```twig
{{ void(my_function({'a' : 'b'})) }}
{{ void(myObject.my_function({'a' : 'b'})) }}
```

## Установка свойств объекта

Специальная функция `set` позволяет устанавливать свойства объекта. Например, следующий фрагмент кода изменяет
title страницы:

```twig
{{ set(this, 'title', 'New title') }}
```

## Импортирование виджетов, пространств имен и классов

Вы можете импортировать в шаблон классы и пространства имен следующим образом:

```twig
Импортирование пространства имен:
{{ use('/app/widgets') }}

Импортирование класса:
{{ use('/yii/widgets/ActiveForm') }}

Импортирование класса с использованием псевдонима:
{{ use({'alias' : '/app/widgets/MyWidget'}) }}
```
Подробная информация находится в разделе [Шаблоны и виджеты](layouts-and-widgets.md)

## Импортирование других классов

В большинстве случаев, кроме виджетов и ассетов, вам понадобится импортировать классы через секцию [globals](additional-configuration.md#globals).
 
Например, этот код ничего не выведет:

```twig
{{ use('yii/helpers/Url') }}
<h1>{{ Url.base(true) }}</h1>
```

и этот код тоже ничего не выведет:

```twig
{{ use ('app/models/MyClass') }}  
{{ MyClass.helloWorld() }}
```

Вы должны указать эти классы в конфигурации, используя секцию [globals](additional-configuration.md#globals):

```php
// ....
'view' => [
    'class' => 'yii\web\View',
    'renderers' => [
        'twig' => [
            'class' => 'yii\twig\ViewRenderer',
            'cachePath' => '@runtime/Twig/cache',
            'options' => [
                'auto_reload' => true,
            ],
            'globals' => [
                'Url' => ['class' => '\yii\helpers\Url'],
                'MyClass' => ['class' => '\frontend\models\MyClass'],
            ],
        ],
    ],
],
// ....
```

Только после этого вы можете использовать классы таким образом:

```twig
<h1>{{ Url.base(true) }}</h1>
{{ MyClass.helloWorld() }}
```

## Интеграция шаблонов

Интегрировать другие шаблоны в текущий шаблон можно с помощью двух операторов `include` и `extends`:

```twig
{% include "comment.twig" %}
{% extends "post.twig" %}

{% include "@app/views/snippets/avatar.twig" %}
{% extends "@app/views/layouts/2columns.twig" %}
```

В первом случае файл вида будет искаться относительно текущего пути. Это значит что файлы `comment.twig` и `post.twig` 
будут искаться в той же директории, что и текущий отображаемый шаблон.

Во втором случае мы используем псевдонимы путей. Все псевдонимя Yii, такие как `@app`, доступны по умолчанию.

Вы также можете использовать метод `render` внутри вида:

```
{{ this.render('comment.twig', {'data1' : data1, 'data2' : data2}) | raw }}
```

## Ассеты

Ассеты могут быть зарегистрированы следующим способом (начиная с версии 2.0.4):

```twig
{{ register_asset_bundle('yii/web/JqueryAsset') }}
```

Более подробный синтаксис:

```twig
{{ use('yii/web/JqueryAsset') }}
{{ register_jquery_asset() }}
```

В коде, указанном выше, `register` определяет, что мы работаем с ассетами, а `jquery_asset` переводится в класс 
`JqueryAsset`, который уже импортирован с помощью `use`.

## URLs

Для построения URL-ов вы можете использовать следующие функции:

```php
<a href="{{ path(['blog/view'], {'alias' : post.alias}) }}">{{ post.title }}</a>
<a href="{{ url(['blog/view'], {'alias' : post.alias}) }}">{{ post.title }}</a>
```

Функция `path` генерирует относительный URL, `url` - абсолютный. Внутри себя обе функции используют [[\yii\helpers\Url::to]].

## Дополнительные переменные

Следующие переменные всегда определены в шаблонах Twig:

- `app`, которая соответствует `\Yii::$app`
- `this`, которая соответствует текущему объекту `View`
 
## Блоки

Вы можете определять блоки следующим образом:

```twig
{{ void(this.beginBlock('block1')) }}
now, block1 is set
{{ void(this.endBlock()) }}
```

Затем отображать блоки в основном шаблоне (layout):

```twig
{{ this.blocks['block1'] }}
```