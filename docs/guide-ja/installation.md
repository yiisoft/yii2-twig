インストール
============

インストールは二つの部分から成ります。
すなわち、composer パッケージの取得と、アプリケーションの構成です。 

## エクステンションをインストールする

このエクステンションをインストールするのに推奨される方法は [composer](http://getcomposer.org/download/) によるものです。

下記のコマンドを実行してください。

```
php composer.phar require --prefer-dist yiisoft/yii2-twig
```

または、あなたの `composer.json` ファイルの `require` セクションに、下記を追加してください。

```
"yiisoft/yii2-twig": "~2.0.0"
```

## アプリケーションを構成する

Twig を使い始めるためには、`view` コンポーネントを下記のように構成する必要があります。

```php
[
    'components' => [
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Twig のオプションの配列
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => ['html' => '\yii\helpers\Html'],
                    'uses' => ['yii\bootstrap'],
                ],
                // ...
            ],
        ],
    ],
]
```

構成が終った後、拡張子 `.twig` を持つファイルにテンプレートを作成することが出来ます。
(別のファイル拡張子を使う場合は、それに応じてコンポーネントの構成を修正してください。)
通常のビューファイルとは異なって、Twig を使用する場合は、コントローラで `$this->render()` を呼ぶときにファイル拡張子を含めなければなりません。

```php
return $this->render('renderer.twig', ['username' => 'Alex']);
```
