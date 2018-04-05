Html ヘルパ
===========

テンプレート内で [[\yii\helpers\Html]] を使うためには、Twig エクステンションを追加しなければなりません。

```php
'extensions' => [
    \yii\twig\html\HtmlHelperExtension::class,
],
```
そして、このようにして使います。

```twig
{{ Html.a('text', 'link', {'class':'btn btn-default'}) }}
```

ただし、いくつかの \yii\helpers\Html メソッドで変数を参照渡しすることが出来ません。
例えば、`addCssClass()` や `addCssStyle()` です。Twig はこれをサポートしていません。

以下のように、追加の式を使って下さい。

```twig
{% set favoriteButtonOptions = {
    'class': 'btn btn-default',
    'style': 'color:red'
} %}

{% css_class favoriteButtonOptions + 'btn-primary' %}
{# Html::addCssClass($favoriteButtonOptions, 'btn-primary') と同じ #}

{% css_class favoriteButtonOptions - 'btn-primary' %}
{# Html::removeCssClass($favoriteButtonOptions, 'btn-primary') と同じ #}

{% css_style favoriteButtonOptions + 'display:none' %}
{# Html::addCssStyle($favoriteButtonOptions, 'display:none') と同じ #}

{% css_style favoriteButtonOptions - 'display' %}
{# Html::removeCssStyle($favoriteButtonOptions, 'display') と同じ #}

{% css_style favoriteButtonOptions - ['display', 'color'] %}
{# Html::removeCssStyle($favoriteButtonOptions, ['display', 'color']) と同じ #}