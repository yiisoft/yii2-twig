レイアウトとウィジェット
========================

## ウィジェット

このエクステンションは、ウィジェットを使うのに便利なように、ウィジェットの構文を関数呼び出しに変換します。

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

上記のテンプレートで、`nav_bar_begin`、`nav_bar_end` または `nav_widget` は、二つの部分から成っています。
最初の部分は、小文字とアンダースコアに変換されたウィジェット名です。
`NavBar` が `nav_bar` になり、`Nav` が `nav` になります。
第二の部分の `_begin`、`_end` そして `_widget` は、ウィジェットの `::begin()`、`::end()` そして `::widget()` メソッドの呼び出しと同じです。

もっと一般的な `Widget::end()` を実行する `widget_end()` を使うことも出来ます。

## メインレイアウト

`views/layout/main.php` を置き換える `views/layout/layout.twig` ファイルの例を示します。

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

```twig
    {{ register_asset_bundle('frontend/assets/AppAsset') }}  {# アドバンストテンプレートのアセットのルート #}
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
## ナビゲーションバー

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

## フッタ

基本的な Yii のフッタのコードを PHP から twig に変換する方法を例示しましょう。

`Powered by Yii framework` を表示するために、構成ファイルの中に `global` を追加します。
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
これがフッタのコードです。
```
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company {{ 'now'|date('Y') }}</p>
        <p class="pull-right">{{ Yii.powered() | raw }}</p>
    </div>
</footer>
```

## フォーム

次のようにして、フォームを構築することが出来ます。

```
{{ use('yii/widgets/ActiveForm') }}
{% set form = active_form_begin({
    'id' : 'login-form',
    'options' : {'class' : 'form-horizontal'},
}) %}
    {{ form.field(model, 'username') | raw }}
    {{ form.field(model, 'password').passwordInput() | raw }}

    <div class="form-group">
        <input type="submit" value="ログイン" class="btn btn-primary" />
    </div>
{{ active_form_end() }}
```
