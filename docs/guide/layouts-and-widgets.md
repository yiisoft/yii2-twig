Layouts and Widgets
===============

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
