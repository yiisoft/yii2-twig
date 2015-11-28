レイアウトとウィジェット
========================

## メイン・レイアウト

`views/layout/layout.twig` file to replace `views/layout/main.php` を置き換える `views/layout/layout.twig` ファイルの例を示します。

デフォルトのレイアウトを変更するために、`SiteController` の中でパブリックな変数を追加します。
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


`views/layout/main.twig` の中身は次のようになります。

```
    {{ register_asset_bundle('frontend/assets/AppAsset') }}  {# アドバンスト・テンプレートのアセットのルート #}
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
                {# ヘッダのコンテント #}
            </section>
            {{ content | raw}}
            <footer class="footer">
                <div class="container">
                    {# フッタのコンテント #}
                </div>
            </footer>
            {{   void(this.endBody()) }}
        </body>
    </html>
    {{   void(this.endPage()) }}
```
## ナビゲーション・バー

前もって `global` を構成情報ファイルの中に追加しましょう。
```php
'renderers' => [
    'twig' => [
        'class' => 'yii\twig\ViewRenderer',
        'cachePath' => '@runtime/Twig/cache',
        // twig のオプションの配列
        'options' => [
            'auto_reload' => true,
        ],
        'globals' => [
            //..
            'url' => '\yii\helpers\Url', // 新しい global
            //..
        ],
        'uses' => ['yii\bootstrap'],
    ],
],
```

以下が login/logout の動的なバリエーションを持つ `navigation` バーのコードです。

```
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
            {# その他 #}
            {
                'label' : 'ログアウト (' ~ app.user.identity.username ~ ')',
                'url' : ['/site/logout'],
                'linkOptions' : {'data-method' : 'post'}
            }
        ])
    %}
    {% else %}
        {% set menuItems = menuItems|merge([
            {'label' : 'ログイン', 'url' : ['/site/login']},
            {# その他 #}
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
