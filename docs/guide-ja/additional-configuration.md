追加の構成
==========

Yii Twig エクステンションは、あなた自身の構文を定義して、通常のヘルパクラスをテンプレートに導入することを可能にしています。
構成のオプションを見ていきましょう。

## グローバル (globals)

アプリケーション構成の `globals` 変数によって、グローバルなヘルパや変数を追加することが出来ます。
Yii のヘルパとあなた自身の変数を定義することが出来ます。

```php
'globals' => [
    'html' => '\yii\helpers\Html',
    'name' => 'Carsten',
    'GridView' => '\yii\grid\GridView',
],
```

いったん構成してしまえば、テンプレートの中で以下のようにグローバルを使用することが出来ます。

```twig
Hello, {{name}}! {{ html.a('ログインしてください', 'site/login') | raw }}.

{{ GridView.widget({'dataProvider' : provider}) | raw }}
```

## 関数 (functions)

追加の関数を次のようにして定義することが出来ます。

```php
'functions' => [
    'rot13' => 'str_rot13',
    'truncate' => '\yii\helpers\StringHelper::truncate',
    new \Twig_SimpleFunction('rot14', 'str_rot13'),
    new \Twig_SimpleFunction('add_*', function ($symbols, $val) {
        return $val . $symbols;
    }, ['is_safe' => ['html']]),
    'callable_add_*' => function ($symbols, $val) {
        return $val . $symbols;
    },
    'sum' => function ($a, $b) {
        return $a + $b;
    }
],
```

テンプレートでは、次のようにして使うことが出来ます。

```twig
{{ rot13('test') }}
{{ truncate(post.text, 100) }}
{{ rot14('test') }}
{{ add_42('answer') }}
{{ callable_add_42('test') }}
{{ sum(1, 2) }}
```

## フィルタ (filters)

追加のフィルタをアプリケーション構成の `filters` オプションによって追加することが出来ます。

```php
'filters' => [
    'jsonEncode' => '\yii\helpers\Json::htmlEncode',
    new \Twig_SimpleFilter('rot13', 'str_rot13'),
    new \Twig_SimpleFilter('add_*', function ($symbols, $val) {
        return $val . $symbols;
    }, ['is_safe' => ['html']]),
    'callable_rot13' => function($string) {
        return str_rot13($string);
    },
    'callable_add_*' => function ($symbols, $val) {
        return $val . $symbols;
    }
],
```

そうすると、テンプレートの中で、次の構文を使ってフィルタを適用することが出来ます。

```twig
{{ model|jsonEncode }}
{{ 'test'|rot13 }}
{{ 'answer'|add_42 }}
{{ 'test'|callable_rot13 }}
{{ 'answer'|callable_add_42 }}
```
