# Upgrading Instructions for Yii 2.0 Twig Extension 

This file contains the upgrade notes for Yii 2.0 Twig Extension. These notes highlight changes that
could break your application when you upgrade extension from one version to another.

Upgrading in general is as simple as updating your dependency in your composer.json and
running `composer update`. In a big application however there may be more things to consider,
which are explained in the following.

> Note: The following upgrading instructions are cumulative. That is,
if you want to upgrade from version A to version C and there is
version B between A and C, you need to follow the instructions
for both A and B.

Upgrade from 2.1.0
------------------
Since 2.2.0 extension uses Twig 2. See [twig upgrade notes](http://symfony.com/blog/twig-how-to-upgrade-to-2-0-deprecation-notices-to-the-rescue).
Also Twig 2 needs at least PHP 7.0.0


Upgrade from 2.0.6
------------------

`path()` and `url()` syntax was changed. Wrap route with array:

```php
{{ path('blog/view', {'alias' : post.alias}) }}
{{ url('blog/view', {'alias' : post.alias}) }}

should become

{{ path(['blog/view'], {'alias' : post.alias}) }}
{{ url(['blog/view'], {'alias' : post.alias}) }}
```

If you're registering globals, adjust your config:

```php
[
    'html' => '\yii\helpers\Html',
]

// should become

[
    'html' => ['class' => '\yii\helpers\Html'],
]
```
