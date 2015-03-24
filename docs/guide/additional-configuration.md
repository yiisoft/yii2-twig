Additional Configuration
========================

Yii Twig extension allows you to define your own syntax and bring regular helper classes into templates. Let's review
configuration options.

## Globals

You can add global helpers or values via the application configuration's `globals` variable. You can define both Yii
helpers and your own variables there:

```php
'globals' => [
    'html' => '\yii\helpers\Html',
    'name' => 'Carsten',
    'GridView' => '\yii\grid\GridView',
],
```

Once configured, in your template you can use the globals in the following way:

```
Hello, {{name}}! {{ html.a('Please login', 'site/login') | raw }}.

{{ GridView.widget({'dataProvider' : provider}) | raw }}
```

## Functions

You can define additional functions like the following:

```php
'functions' => [
    'rot13' => 'str_rot13',
    'truncate' => '\yii\helpers\StringHelper::truncate',
    new \Twig_SimpleFunction('rot14', 'str_rot13'),
    new \Twig_SimpleFunction('add_*', function ($symbols, $val) {
        return $val . $symbols;
    }, ['is_safe' => ['html']])
],
```

In template they could be used like the following:

```
`{{ rot13('test') }}`
`{{ truncate(post.text, 100) }}`
`{{ rot14('test') }}`
`{{ add_42('answer') }}`
```

## Filters

Additional filters may be added via the application configuration's `filters` option:

```php
'filters' => [
    'jsonEncode' => '\yii\helpers\Json::encode',
    new \Twig_SimpleFilter('rot13', 'str_rot13'),
    new \Twig_SimpleFilter('add_*', function ($symbols, $val) {
        return $val . $symbols;
    }, ['is_safe' => ['html']])
],
```

Then in the template you can apply filter using the following syntax:

```
{{ model|jsonEncode }}
{{ 'test'|rot13 }}
{{ 'answer'|add_42 }}
```
