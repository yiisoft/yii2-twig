テンプレートの構文
==================

Twig の基礎を学ぶための最善のリソースは、[twig.sensiolabs.org](http://twig.sensiolabs.org/documentation) にある公式ドキュメントです。
それに追加して、下記に説明する Yii 固有の拡張構文があります。

## メソッドとファンクションの呼び出し

結果が必要な場合は、次の構文を使ってメソッドや関数を呼び出すことが出来ます。

```twig
{% set result = my_function({'a' : 'b'}) %}
{% set result = myObject.my_function({'a' : 'b'}) %}
```

結果を変数に代入する代りに echo したい場合は、こうします。

```twig
{{ my_function({'a' : 'b'}) }}
{{ myObject.my_function({'a' : 'b'}) }}
```

結果を必要としない場合は、`void` ラッパーを使わなければなりません。

```twig
{{ void(my_function({'a' : 'b'})) }}
{{ void(myObject.my_function({'a' : 'b'})) }}
```

## オブジェクトのプロパティを設定する

`set` と呼ばれる特別な関数を使って、オブジェクトのプロパティを設定することが出来ます。
例えば、テンプレート中の下記のコードはページタイトルを変更します。

```twig
{{ set(this, 'title', 'New title') }}
```

## ウィジェットの名前空間とクラスをインポートする

追加のクラスと名前空間をテンプレートの中でインポートすることが出来ます。

```twig
名前空間のインポート:
{{ use('/app/widgets') }}

クラスのインポート:
{{ use('/yii/widgets/ActiveForm') }}

エイリアス化してクラスをインポート:
{{ use({'alias' : '/app/widgets/MyWidget'}) }}
```

追加の情報を [レイアウトとウィジェット](layouts-and-widgets.md) で参照してください。


## その他のクラスをインポートする

たいていの場合、ウィジェットとアセットを除くと、[globals](additional-configuration.md#globals) によってクラスをインポートしなければなりません。

例えば、次のコードは何も表示しません。

```
{{ use('yii/helpers/Url') }}
<h1>{{ Url.base(true) }}</h1>
```

次のコードも何も表示しません。

```
{{ use ('app/models/MyClass') }}  
{{ MyClass.helloWorld() }}
```

これらのクラスは、[globals](additional-configuration.md#globals) に追加しなければなりません。

```
// ....
'view' => [
    'class' => 'yii\web\View',
    'renderers' => [
        'twig' => [
            'class' => 'yii\twig\ViewRenderer',
            'cachePath' => '@runtime/Twig/cache',
            'options' => [
                'auto_reload' => true,
            ],
            'globals' => [
                'Url' => '\yii\helpers\Url',
                'MyClass' => '\frontend\models\MyClass',
            ],
        ],
    ],
],
// ....
```

こうして、はじめて、クラスをそのように使うことが出来ます。
```
<h1>{{ Url.base(true) }}</h1>
{{ MyClass.helloWorld() }}
```


## 他のテンプレートを参照する

`include` と `extends` 文によるテンプレートの参照には二つの方法があります。

```twig
{% include "comment.twig" %}
{% extends "post.twig" %}

{% include "@app/views/snippets/avatar.twig" %}
{% extends "@app/views/layouts/2columns.twig" %}
```

最初の場合では、現在のテンプレートのパスからの相対的なパスでビューを探しています。
すなわち、`comment.twig` と `post.twig` は、現在レンダリングされているテンプレートと同じディレクトリで探されるということを意味します。

第二の場合では、パスエイリアスを使っています。
`@app` のような Yii のエイリアスの全てがデフォルトで利用できます。

また、ビューの中で `render` メソッドを使うことも出来ます。
```
{{ this.render('comment.twig', {'data1' : data1, 'data2' : data2}) | raw }}
```

## アセット

アセットは次の方法で登録することが出来ます (2.0.4 以降)。

```twig
{{ register_asset_bundle('yii/web/JqueryAsset') }}
```

以前は、もう少し饒舌な文法が使われていました。

```twig
{{ use('yii/web/JqueryAsset') }}
{{ register_jquery_asset() }}
```

上記のコードで、`register` は、アセットを扱うことを指定し、`jquery_asset` は、既に `use` でインポート済みの `JqueryAsset` クラスに翻訳されます。


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

```twig
{{ void(this.beginBlock('block1')) }}
これで block1 が設定されました
{{ void(this.endBlock()) }}
```

そして、レイアウトビューで、ブロックをレンダリングします。

```twig
{{ this.blocks['block1'] }}
```
