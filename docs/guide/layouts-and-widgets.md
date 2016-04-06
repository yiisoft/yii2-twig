Layouts and Widgets
===============

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

## Main layout

Here is an example of `views/layout/layout.twig` file to replace `views/layout/main.php`. 

In order to change default layout add public variable inside `SiteController`:
```php
/**
 * Site controller
 */
class SiteController extends Controller
{
    // ..
    public $layout = 'main.twig';
    // ..
```


Here is code inside file `views/layout/main.twig`: 

```twig
    {{ register_asset_bundle('frontend/assets/AppAsset') }}  {# asset root for yii advanced template #}
    {{   void(this.beginPage()) }}
    <!DOCTYPE html>
    <html lang="{{ app.language }}">
        <head>
            <meta charset="{{ app.charset }}">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>{{ html.encode(this.title) }}</title>
            {{ html.csrfMetaTags | raw }}
            {{   void(this.head) }}
        </head>
        <body>
            {{   void(this.beginBody()) }}
            <section id="header">
                {# header content #}
            </section>
            {{ content | raw}}
            <footer class="footer">
                <div class="container">
                    {# footer content #}
                </div>
            </footer>
            {{   void(this.endBody()) }}
        </body>
    </html>
    {{   void(this.endPage()) }}
```
## Navigation bar

Beforehand let's add `global` inside config file:
```php
'renderers' => [
    'twig' => [
        'class' => 'yii\twig\ViewRenderer',
        'cachePath' => '@runtime/Twig/cache',
        // Array of twig options:
        'options' => [
            'auto_reload' => true,
        ],
        'globals' => [
            //..
            'url' => '\yii\helpers\Url', // new global
            //..
        ],
        'uses' => ['yii\bootstrap'],
    ],
],
```

Here is `navigation` bar code with login/logout dynamic variants:

```twig
    {{ nav_bar_begin({
        'brandLabel': '<div class="logo"><img src="' ~ url.base(true) ~'/images/png/logo.png" alt="logo"/></div>',
        'brandUrl' : app.homeUrl,
        'options' : {
            'class' : 'header navbar navbar-fixed-top',
        }
    }) }}
    {% set menuItems = [] %}
    {% if app.user.isGuest == false %}
        {% set menuItems = menuItems|merge([
            {'label' : 'Main', 'url' : ['/site/index']},
            {'label' : 'About', 'url' : ['/site/about']},
            {# Other ones #}
            {
                'label' : 'logout (' ~ app.user.identity.username ~ ')',
                'url' : ['/site/logout'],
                'linkOptions' : {'data-method' : 'post'}
            }
        ])
    %}
    {% else %}
        {% set menuItems = menuItems|merge([
            {'label' : 'login', 'url' : ['/site/login']},
            {# Other ones #}
        ])
    %}
    {% endif %}
    {{ nav_widget({
        'options': {
            'class': 'navbar-nav navbar-right',
        },
        'items': menuItems
    }) }}
    {{ nav_bar_end() }}
```

## Footer

Here is an example how to convert basic Yii footer code from PHP to twig.

In order to show `Powered by Yii framework` add `global` inside config file:
```php
'renderers' => [
    'twig' => [
        //..
        'globals' => [
            //..
            'Yii' => '\Yii',
            //..
        ],
        'uses' => ['yii\bootstrap'],
        //..
    ],
],
```
Here is a footer code:
```
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company {{ 'now'|date('Y') }}</p>
        <p class="pull-right">{{ Yii.powered() | raw }}</p>
    </div>
</footer>
```

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