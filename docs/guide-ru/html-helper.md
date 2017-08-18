Html хелпер
===========

Для использования \yii\helpers\Html в шаблонах можно воспользоваться Twig расширением

```php
'extensions' => [
    \yii\twig\html\HtmlHelperExtension::class,
],
```

и использовать хелпер следующим образом:

```twig
{{ Html.a('text', 'link', {'class':'btn btn-default'}) }}
```

Но существует ограничение на работу с методами хелпера, которые в качестве аргументов принимают ссылки на массивы, 
например методы addCssClass или addCssStyle. Twig не поддерживает такую передачу аргументов.

Работа с этими методами возможна с помощью дополнительных выражений:

```twig
{% set favoriteButtonOptions = {
    'class': 'btn btn-default',
    'style': 'color:red'
} %}

{% css_class favoriteButtonOptions + 'btn-primary' %}
{# эквивалент Html::addCssClass($favoriteButtonOptions, 'btn-primary') #}

{% css_class favoriteButtonOptions - 'btn-primary' %}
{# эквивалент Html::removeCssClass($favoriteButtonOptions, 'btn-primary') #}

{% css_style favoriteButtonOptions + 'display:none' %}
{# эквивалент Html::addCssStyle($favoriteButtonOptions, 'display:none') #}

{% css_style favoriteButtonOptions - 'display' %}
{# эквивалент Html::removeCssStyle($favoriteButtonOptions, 'display') #}
или
{% css_style favoriteButtonOptions - ['display', 'color'] %}
{# эквивалент Html::removeCssStyle($favoriteButtonOptions, ['display', 'color']) #}
```
