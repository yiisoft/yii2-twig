Template Syntax
===============

The best resource to learn Twig basics is its official documentation you can find at
[twig.sensiolabs.org](http://twig.sensiolabs.org/documentation). Additionally there are Yii-specific syntax extensions
described below.

## Method and function calls

If you need result you can call a method or a function using the following syntax:

```twig
{% set result = my_function({'a' : 'b'}) %}
{% set result = myObject.my_function({'a' : 'b'}) %}
```

If you need to echo result instead of assigning it to a variable:

```twig
{{ my_function({'a' : 'b'}) }}
{{ myObject.my_function({'a' : 'b'}) }}
```

In case you don't need result you shoud use `void` wrapper:

```twig
{{ void(my_function({'a' : 'b'})) }}
{{ void(myObject.my_function({'a' : 'b'})) }}
```

## Setting object properties

There's a special function called `set` that allows you to set property of an object. For example, the following
in the template will change page title:

```twig
{{ set(this, 'title', 'New title') }}
```

## Importing widgets namespaces and classes

You can import additional classes and namespaces right in the template:

```twig
Namespace import:
{{ use('/app/widgets') }}

Class import:
{{ use('/yii/widgets/ActiveForm') }}

Aliased class import:
{{ use({'alias' : '/app/widgets/MyWidget'}) }}
```
Please refer to [Layouts and Widgets](layouts-and-widgets.md) for additional information.


## Importing other classes

In most cases, except widgets and assets, you have to import classes via [globals](additional-configuration.md#globals).
 
For example this code prints nothing:

```
{{ use('yii/helpers/Url') }}
<h1>{{ Url.base(true) }}</h1>
```

and this code also prints nothing:

```
{{ use ('app/models/MyClass') }}  
{{ MyClass.helloWorld() }}
```

You have add these classes to [globals](additional-configuration.md#globals):

```
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
                'Url' => '\yii\helpers\Url',
                'MyClass' => '\frontend\models\MyClass',
            ],
        ],
    ],
],
// ....
```

Only then you can use classes such way:
```
<h1>{{ Url.base(true) }}</h1>
{{ MyClass.helloWorld() }}
```


## Referencing other templates

There are two ways of referencing templates in `include` and `extends` statements:

```twig
{% include "comment.twig" %}
{% extends "post.twig" %}

{% include "@app/views/snippets/avatar.twig" %}
{% extends "@app/views/layouts/2columns.twig" %}
```

In the first case the view will be searched relatively to the current template path. For `comment.twig` and `post.twig`
that means these will be searched in the same directory as the currently rendered template.

In the second case we're using path aliases. All the Yii aliases such as `@app` are available by default.

You can also use `render` method inside a view:
```
{{ this.render('comment.twig', {'data1' : data1, 'data2' : data2}) | raw }}
```

## Assets

Assets could be registered the following way (since 2.0.4):

```twig
{{ register_asset_bundle('yii/web/JqueryAsset') }}
```

There's a bit more verbose syntax used previously:

```twig
{{ use('yii/web/JqueryAsset') }}
{{ register_jquery_asset() }}
```

In the call above `register` identifies that we're working with assets while `jquery_asset` translates to `JqueryAsset`
class that we've already imported with `use`.

## URLs

There are two functions you can use for building URLs:

```php
<a href="{{ path('blog/view', {'alias' : post.alias}) }}">{{ post.title }}</a>
<a href="{{ url('blog/view', {'alias' : post.alias}) }}">{{ post.title }}</a>
```

`path` generates relative URL while `url` generates absolute one. Internally both are using [[\yii\helpers\Url]].

## Additional variables

Within Twig templates the following variables are always defined:

- `app`, which equates to `\Yii::$app`
- `this`, which equates to the current `View` object
 
## Blocks

You can set blocks the following way:

```twig
{{ void(this.beginBlock('block1')) }}
now, block1 is set
{{ void(this.endBlock()) }}
```

Then, in the layout view, render the blocks:

```twig
{{ this.blocks['block1'] }}
```
