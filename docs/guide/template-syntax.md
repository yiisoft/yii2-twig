Template Syntax
===============

The best resource to learn Twig basics is its official documentation you can find at
[twig.sensiolabs.org](http://twig.sensiolabs.org/documentation). Additionally there are Yii-specific syntax extensions
described below.

## Method and function calls

If you need result you can call a method or a function using the following syntax:

```
{% set result = my_function({'a' : 'b'}) %}
{% set result = myObject.my_function({'a' : 'b'}) %}
```

If you need to echo result instead of assigning it to a variable:

```
{{ my_function({'a' : 'b'}) }}
{{ myObject.my_function({'a' : 'b'}) }}
```

In case you don't need result you shoud use `void` wrapper:

```
{{ void(my_function({'a' : 'b'})) }}
{{ void(myObject.my_function({'a' : 'b'})) }}
```

## Setting object properties

There's a special function called `set` that allows you to set property of an object. For example, the following
in the template will change page title:

```
{{ set(this, 'title', 'New title') }}
```

## Importing namespaces and classes

You can import additional classes and namespaces right in the template:

```
Namespace import:
{{ use('/app/widgets') }}

Class import:
{{ use('/yii/widgets/ActiveForm') }}

Aliased class import:
{{ use({'alias' : '/app/widgets/MyWidget'}) }}
```

## Referencing other templates

There are two ways of referencing templates in `include` and `extends` statements:

```
{% include "comment.twig" %}
{% extends "post.twig" %}

{% include "@app/views/snippets/avatar.twig" %}
{% extends "@app/views/layouts/2columns.twig" %}
```

In the first case the view will be searched relatively to the current template path. For `comment.twig` and `post.twig`
that means these will be searched in the same directory as the currently rendered template.

In the second case we're using path aliases. All the Yii aliases such as `@app` are available by default.

## Widgets

Extension helps using widgets in convenient way converting their syntax to function calls:

```
{{ use('yii/bootstrap') }}
{{ nav_bar_begin({
    'brandLabel': 'My Company',
}) }}
    {{ nav_widget({
        'options': {
            'class': 'navbar-nav navbar-right',
        },
        'items': [{
            'label': 'Home',
            'url': '/site/index',
        }]
    }) }}
{{ nav_bar_end() }}
```

In the template above `nav_bar_begin`, `nav_bar_end` or `nav_widget` consists of two parts. First part is widget name
coverted to lowercase and underscores: `NavBar` becomes `nav_bar`, `Nav` becomes `nav`. `_begin`, `_end` and `_widget`
are the same as `::begin()`, `::end()` and `::widget()` calls of a widget.

One could also use more generic `widget_end()` that executes `Widget::end()`.

## Assets

Assets could be registered the following way (since 2.0.4):

```
{{ register_asset_bundle('yii/web/JqueryAsset') }}
```

There's a bit more verbose syntax used previously:

```
{{ use('yii/web/JqueryAsset') }}
{{ register_jquery_asset() }}
```

In the call above `register` identifies that we're working with assets while `jquery_asset` translates to `JqueryAsset`
class that we've already imported with `use`.

## Forms

You can build forms the following way:

```
{{ use('yii/widgets/ActiveForm') }}
{% set form = active_form_begin({
    'id' : 'login-form',
    'options' : {'class' : 'form-horizontal'},
}) %}
    {{ form.field(model, 'username') | raw }}
    {{ form.field(model, 'password').passwordInput() | raw }}

    <div class="form-group">
        <input type="submit" value="Login" class="btn btn-primary" />
    </div>
{{ active_form_end() }}
```


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

```
{{ void(this.beginBlock('block1')) }}
now, block1 is set
{{ void(this.endBlock()) }}
```

Then, in the layout view, render the blocks:

```
{{ this.blocks['block1'] }}
```
