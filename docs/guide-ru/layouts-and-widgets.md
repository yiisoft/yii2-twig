Шаблоны и виджеты
=================

## Виджеты

Расширение позволяет использовать виджеты в удобной форме, конвертируя их синтаксис в вызовы функций:

```twig
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

В шаблоне выше имена функций `nav_bar_begin`, `nav_bar_end` или `nav_widget` состоят из двух частей. Первая часть - 
это имя виджета, переведенное в нижний регистр и разделенное нижними подчеркиваниями: `NavBar` становится `nav_bar`, 
`Nav` становится `nav`. `_begin`, `_end` и `_widget` аналогичны вызовам виджета `::begin()`, `::end()` и `::widget()`.

Можно также использовать более общий вызов функции `widget_end()`, который выполняет `Widget::end()`.

Идеология Twig не позволяет применять php-код в шаблонах. Поэтому если есть необходимость использования анонимных функций 
при конфигурации виджетов, эта функция может быть передана в шаблон в качестве переменной. 

```php
/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actionIndex()
    {
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setModels([
            [
                'id' => 1,
                'name' => 'First',
                'checked' => false,
            ],
            [
                'id' => 2,
                'name' => 'Second',
                'checked' => true,
            ],
            [
                'id' => 1,
                'name' => 'third',
                'checked' => false,
            ],
        ]);

        $someFunction = function ($model) {
            return $model['checked'] === true ? 'yes' : 'no';
        };

        return $this->render('index.twig', [
            'dataProvider' => $dataProvider,
            'someFunction' => $someFunction
        ]);    
    }
}
```

```twig
{{ use('yii/grid/GridView') }}
{{ grid_view_widget({
    'dataProvider': dataProvider,
    'columns': [
        {'class': '\\yii\\grid\\SerialColumn'},
        'id',
        'name',
        {
            'attribute': 'checked',
            'value': someFunction
        }
    ]
})
}}
```

## Главный шаблон

Рассмотрим пример, как заменить шаблон по умолчанию `views/layout/main.php` на файл `views/layout/main.twig`.

Чтобы изменить шаблон по умолчанию, добавьте public свойство в контроллер `SiteController`:

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

Код в шаблоне `views/layout/main.twig`: 

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

Чтобы не использовать шаблон контроллера, необходимо задать значение `false` для свойства `$layout`. 
Также можно задать его глобально для всех контроллеров в конфигурации приложения.

```php
[
    'layout' => false
]
```

## Панель навигации

Прежде всего добавим `global` в файл конфигурации:

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
            'url' => ['class' => '\yii\helpers\Url'], // new global
            //..
        ],
        'uses' => ['yii\bootstrap'],
    ],
],
```

Пример кода панели навигации в двух вариантах - для зарегистрированных/незарегистрированных пользователей:

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

## Футер

Рассмотрим пример, как конвертировать стандартный футер Yii из PHP в шаблон Twig.

Чтобы отобразить `Powered by Yii framework` добавим `global` в файл конфигурации:

```php
'renderers' => [
    'twig' => [
        //..
        'globals' => [
            //..
            'Yii' => ['class' => '\Yii'],
            //..
        ],
        'uses' => ['yii\bootstrap'],
        //..
    ],
],
```

Код футера:

```twig
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company {{ 'now'|date('Y') }}</p>
        <p class="pull-right">{{ Yii.powered() | raw }}</p>
    </div>
</footer>
```

## Формы

Вы можете строить формы следующим способом:

```twig
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