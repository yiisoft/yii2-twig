テンプレート構文
================

Twig の基礎を学ぶための最善のリソースは、[twig.sensiolabs.org](http://twig.sensiolabs.org/documentation) にある公式ドキュメントです。
それに追加して、下記に説明する Yii 固有の拡張構文があります。

## メソッドとファンクションの呼び出し

結果が必要な場合は、次の構文を使ってメソッドや関数を呼び出すことが出来ます。

```
{% set result = my_function({'a' : 'b'}) %}
{% set result = myObject.my_function({'a' : 'b'}) %}
```

結果を変数に代入する代りに echo したい場合は、こうします。

```
{{ my_function({'a' : 'b'}) }}
{{ myObject.my_function({'a' : 'b'}) }}
```

結果を必要としない場合は、`void` ラッパーを使わなければなりません。

```
{{ void(my_function({'a' : 'b'})) }}
{{ void(myObject.my_function({'a' : 'b'})) }}
```

## オブジェクトのプロパティを設定する

`set` と呼ばれる特別な関数を使って、オブジェクトのプロパティを設定することが出来ます。
例えば、テンプレート中の下記のコードはページタイトルを変更します。

```
{{ set(this, 'title', 'New title') }}
```

## 名前空間とクラスをインポートする

追加のクラスと名前空間をテンプレートの中でインポートすることが出来ます。

```
名前空間のインポート:
{{ use('/app/widgets') }}

クラスのインポート:
{{ use('/yii/widgets/ActiveForm') }}

エイリアス化してクラスをインポート:
{{ use({'alias' : '/app/widgets/MyWidget'}) }}
```

## 他のテンプレートを参照する

`include` と `extends` 文によるテンプレートの参照には二つの方法があります。

```
{% include "comment.twig" %}
{% extends "post.twig" %}

{% include "@app/views/snippets/avatar.twig" %}
{% extends "@app/views/layouts/2columns.twig" %}
```

最初の場合では、現在のテンプレートのパスからの相対的なパスでビューを探します。
`comment.twig` と `post.twig` は、現在レンダリングされているテンプレートと同じディレクトリで探されます。

第二の場合では、パスエイリアスを使います。
`@app` のような全ての Yii のエイリアスがデフォルトで利用できます。

## ウィジェット

このエクステンションは、ウィジェットを簡単に使えるように、ウィジェットの構文を関数呼び出しに変換します。

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

上記のテンプレートでは、`nav_bar_begin`、`nav_bar_end` また `nav_widget` は、二つの部分から構成されます。
最初の部分は、小文字とアンダースコアに変換されたウィジェットの名前です。
`NavBar` は `nav_bar`、`Nav` は `nav` に変換されます。
第二の部分の `_begin`、`_end` および `_widget` は、ウィジェットのメソッド `::begin()`、`::end()` および `::widget()` と同じものです。

もっと汎用的な `Widget::end()` を実行する `widget_end()` も使うことが出来ます。

## アセット

アセットは次の方法で登録することが出来ます。

```
{{ use('yii/web/JqueryAsset') }}
{{ register_jquery_asset() }}
```

上記のコードで、`register` は、アセットを扱うことを指定し、`jquery_asset` は、既に `use` でインポート済みの `JqueryAsset` クラスに翻訳されます。

## フォーム

フォームは次のようにして構築することが出来ます。

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


## URL

URL を構築するのに使える二つの関数があります。

```php
<a href="{{ path('blog/view', {'alias' : post.alias}) }}">{{ post.title }}</a>
<a href="{{ url('blog/view', {'alias' : post.alias}) }}">{{ post.title }}</a>
```

`path` は相対的な URL を生成し、`url` は絶対的な URL を生成します。
内部的には、両者とも、[[\yii\helpers\Url]] を使っています。

## 追加の変数

Twig のテンプレート内では、次の変数が常に定義されています。

- `app` - `\Yii::$app` オブジェクト
- `this` - 現在の `View` オブジェクト

 
## ブロック

次のようにしてブロックを設定することが出来ます。

```
{{ void(this.beginBlock('block1')) }}
これで block1 が設定されました
{{ void(this.endBlock()) }}
```

そして、レイアウトビューで、ブロックをレンダリングします。

```
{{ this.blocks['block1'] }}
```
