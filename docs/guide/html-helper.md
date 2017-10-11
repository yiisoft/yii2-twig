Html helper
===========

To use [[\yii\helpers\Html]] in templates you must add a Twig extension

```php
'extensions' => [
    \yii\twig\html\HtmlHelperExtension::class,
],
```
and use it this way

```twig
{{ Html.a('text', 'link', {'class':'btn btn-default'}) }}
```

But you can't pass variables by reference for some \yii\helpers\Html methods. For example `addCssClass()` 
or `addCssStyle()`. Twig does not support this.

Use additional expression:

```twig
{% set favoriteButtonOptions = {
    'class': 'btn btn-default',
    'style': 'color:red'
} %}

{% css_class favoriteButtonOptions + 'btn-primary' %}
{# equivalent Html::addCssClass($favoriteButtonOptions, 'btn-primary') #}

{% css_class favoriteButtonOptions - 'btn-primary' %}
{# equivalent Html::removeCssClass($favoriteButtonOptions, 'btn-primary') #}

{% css_style favoriteButtonOptions + 'display:none' %}
{# equivalent Html::addCssStyle($favoriteButtonOptions, 'display:none') #}

{% css_style favoriteButtonOptions - 'display' %}
{# equivalent Html::removeCssStyle($favoriteButtonOptions, 'display') #}
или
{% css_style favoriteButtonOptions - ['display', 'color'] %}
{# equivalent Html::removeCssStyle($favoriteButtonOptions, ['display', 'color']) #}