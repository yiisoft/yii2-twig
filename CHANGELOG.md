Yii Framework 2 twig extension Change Log
=========================================

2.4.1 November 10, 2020
-----------------------

- Chg: Allow installing on PHP 8 (samdark)


2.4.0 March 13, 2020
--------------------

- Enh #116: Add `t()` function (samdark)
- Enh #123: Upgrade to Twig 3 (andrew-nuwber)


2.3.0 February 06, 2020
-----------------------

- Bug #115: Replace deprecated PSR-0 classes and add compatibility for Twig 2.7+ versions (boliver20)


2.2.2 February 06, 2020
-----------------------

- Bug #103: Fixed HtmlHelperExtension issue (boliver20)


2.2.1 September 24, 2018
------------------------

- Bug #97: Fixed error when outputting DateTime dates (koxu1996)


2.2.0 November 7, 2017
----------------------

- Enh #84 Upgrade to Twig 2 (koxu1996)
- Enh #90 Added `yii\twig\Profile`. Extension for render profiling (amarox)


2.1.1 October 11, 2017
----------------------

- Enh #17: Added html helper (amarox)
- Enh #25: Improve exceptions (amarox)
- Enh #75: Got rid of deprecated function `Twig\Node::getLine()` (dmirogin)
- Enh #78: Added `ViewRendererStaticClassProxy:__isset()` to be able to access static variable in the template (mrlinqu)
- Enh #79: Added class constants support (mrlinqu)


2.1.0 March 25, 2017
--------------------

- Enh #43: Added scalar global variables support (mpestov)
- Enh #71: Added DIC usage for instantiating of extensions (ElisDN)
- Chg #46: Adjusted `path()` and `url()` syntax to be similar to Yii's `Url::to()` (quantum13)


2.0.6 October 5, 2016
---------------------

- Bug #61: Added missing view object existence check (Quexer69)


2.0.5 September 4, 2016
-----------------------

- Enh #16: Extended simple functions and simple filters support (PatchRanger, quantum13)
- Enh #30: Added `@app/views`, `@app/modules`, `@app/widgets` as `Twig_Loader_Filesystem` loader paths, same for theme `pathMap` paths (andrew-kamenchuk)
- Enh #40: Added ability for `register_asset_bundle()` to return `AssetBundle` instance when second argument is `true` (gta4kv)


2.0.4 May 10, 2015
------------------

- Enh #10: Added alternative syntax for registering asset bundles `{{ register_asset_bundle('yii/web/JqueryAsset') }}` (quantum13)
- Enh #11: Added support for `Twig_SimpleFunction` and `Twig_SimpleFilter` when defining functions and filters (quantum13)


2.0.3 March 01, 2015
--------------------

- no changes in this release.


2.0.2 January 11, 2015
----------------------

- Bug #6464: `path` and `url` weren't resolving aliases (samdark, lynicidn)


2.0.1 December 07, 2014
-----------------------

- no changes in this release.


2.0.0 October 12, 2014
----------------------

- Bug #5308: object function calls in templates were passing arguments in a wrong way (genichyar, samdark)


2.0.0-rc September 27, 2014
---------------------------

- Bug #2925: Fixed throwing exception when accessing AR property with null value (samdark)
- Bug #3767: Fixed repeated adding of extensions when using config. One may now pass extension instances as well (grachov)
- Bug #3877: Fixed `lexerOptions` throwing exception (dapatrese)
- Bug #4290: Fixed throwing exception when trying to access AR relation that is null (samdark, tenitski)
- Bug #5191: Sandbox was ignored for models and AR relations (genichyar)
- Enh #1799: Added `form_begin`, `form_end` to twig extension (samdark)
- Enh #3674: Various enhancements (samdark)
    - Removed `FileLoader` and used `\Twig_Loader_Filesystem` instead.
    - Added support of Yii's aliases.
    - Added `set()` that allows setting object properties.
- Chg #3535: Syntax changes:
    - Removed `form_begin`, `form_end` (samdark)
    - Added `use()` and `ViewRenderer::uses` that are importing classes and namespaces (grachov, samdark)
    - Added widget dynamic functions `*_begin`, `*_end`, `*_widget`, `widget_end` (grachov, samdark)
    - Added more tests (samdark)
- Chg: Renamed `TwigSimpleFileLoader` into `FileLoader` (samdark)

2.0.0-beta April 13, 2014
-------------------------

- Added file based Twig loader for better caching and usability of Twig's file based functions (dev-mraj, samdark)

2.0.0-alpha, December 1, 2013
-----------------------------

- Initial release.



