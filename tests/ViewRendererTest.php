<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yiiunit\twig;

use yii\helpers\FileHelper;
use yii\web\AssetManager;
use yii\web\View;
use Yii;
use yiiunit\twig\data\Order;
use yiiunit\twig\data\Singer;

/**
 * Tests Twig view renderer
 *
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @author Carsten Brandt <mail@cebe.cc>
 */
class ViewRendererTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        Order::setUp();
    }

    protected function setUp()
    {
        parent::setUp();
        $this->mockWebApplication();
    }

    protected function tearDown()
    {
        parent::tearDown();
        FileHelper::removeDirectory(Yii::getAlias('@runtime/assets'));
    }

    /**
     * https://github.com/yiisoft/yii2/issues/1755
     */
    public function testLayoutAssets()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/twig/views/layout.twig');

        $this->assertEquals(1, preg_match('#<script src="/assets/[0-9a-z]+/jquery\\.js"></script>\s*</body>#', $content), 'Content does not contain the jquery js:' . $content);
    }

    public function testAppGlobal()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/twig/views/layout.twig');

        $this->assertEquals(1, preg_match('#<meta charset="' . Yii::$app->charset . '"/>#', $content), 'Content does not contain charset:' . $content);
    }

    /**
     * https://github.com/yiisoft/yii2/issues/3877
     */
    public function testLexerOptions()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/twig/views/comments.twig');

        $this->assertFalse(strpos($content, 'CUSTOM_LEXER_TWIG_COMMENT'), 'Custom comment lexerOptions were not applied: ' . $content);
        $this->assertTrue(strpos($content, 'DEFAULT_TWIG_COMMENT') !== false, 'Default comment style was not modified via lexerOptions:' . $content);
    }

    public function testForm()
    {
        $view = $this->mockView();
        $model = new Singer();
        $content = $view->renderFile('@yiiunit/twig/views/form.twig', ['model' => $model]);
        $this->assertEquals(1, preg_match('#<form id="login-form" class="form-horizontal" action="/form-handler" method="post">.*?</form>#s', $content), 'Content does not contain form:' . $content);
    }

    public function testCalls()
    {
        $view = $this->mockView();
        $model = new Singer();
        $content = $view->renderFile('@yiiunit/twig/views/calls.twig', ['model' => $model]);
        $this->assertNotContains('silence', $content, 'silence should not be echoed when void() used');
        $this->assertContains('echo', $content);
        $this->assertContains('variable', $content);
    }

    public function testInheritance()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/twig/views/extends2.twig');
        $this->assertContains('Hello, I\'m inheritance test!', $content);
        $this->assertContains('extends2 block', $content);
        $this->assertNotContains('extends1 block', $content);

        $content = $view->renderFile('@yiiunit/twig/views/extends3.twig');
        $this->assertContains('Hello, I\'m inheritance test!', $content);
        $this->assertContains('extends3 block', $content);
        $this->assertNotContains('extends1 block', $content);
    }

    public function testChangeTitle()
    {
        $view = $this->mockView();
        $view->title = 'Original title';

        $content = $view->renderFile('@yiiunit/twig/views/changeTitle.twig');
        $this->assertContains('New title', $content);
        $this->assertNotContains('Original title', $content);
    }

    public function testNullsInAr()
    {
        $view = $this->mockView();
        $order = new Order();
        $content = $view->renderFile('@yiiunit/twig/views/nulls.twig', ['order' => $order]);
        $this->assertSame('', $content);
    }

    public function testPropertyAccess()
    {
        $view = $this->mockView();
        $order = new Order();
        $order->total = 42;
        $content = $view->renderFile('@yiiunit/twig/views/property.twig', ['order' => $order]);
        $this->assertContains('42', $content);
    }

    public function testSimpleFilters()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/twig/views/simpleFilters1.twig');
        $this->assertEquals($content, 'Gjvt');
        $content = $view->renderFile('@yiiunit/twig/views/simpleFilters2.twig');
        $this->assertEquals($content, 'val42');
        $content = $view->renderFile('@yiiunit/twig/views/simpleFilters3.twig');
        $this->assertEquals($content, 'Gjvt');
        $content = $view->renderFile('@yiiunit/twig/views/simpleFilters4.twig');
        $this->assertEquals($content, 'val42');
        $content = $view->renderFile('@yiiunit/twig/views/simpleFilters5.twig');
        $this->assertEquals($content, 'Gjvt');
    }

    public function testSimpleFunctions()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/twig/views/simpleFunctions1.twig');
        $this->assertEquals($content, 'Gjvt');
        $content = $view->renderFile('@yiiunit/twig/views/simpleFunctions2.twig');
        $this->assertEquals($content, 'val43');
        $content = $view->renderFile('@yiiunit/twig/views/simpleFunctions3.twig');
        $this->assertEquals($content, 'Gjvt');
        $content = $view->renderFile('@yiiunit/twig/views/simpleFunctions4.twig');
        $this->assertEquals($content, 'val43');
        $content = $view->renderFile('@yiiunit/twig/views/simpleFunctions5.twig');
        $this->assertEquals($content, '6');
    }

    public function testHtmlExtension()
    {
        $params = [
            'options' => [
                'class' => 'btn btn-default',
                'style' => 'color:red; font-size: 24px'
            ]
        ];
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/twig/views/html/add_class.twig', $params);
        $this->assertEquals($content, "btn btn-default btn-primary");
        $content = $view->renderFile('@yiiunit/twig/views/html/remove_class.twig', $params);
        $this->assertEquals($content, "btn");
        $content = $view->renderFile('@yiiunit/twig/views/html/add_style.twig', $params);
        $this->assertEquals($content, "color: red; font-size: 24px; display: none;");
        $content = $view->renderFile('@yiiunit/twig/views/html/remove_style.twig', $params);
        $this->assertEquals($content, "color: red; font-size: 24px;/color: red; font-size: 24px;");
    }

    public function testRegisterAssetBundle()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/twig/views/register_asset_bundle.twig');
        $this->assertEquals(Yii::getAlias('@bower/jquery/dist'), $content);
    }

    public function testPath()
    {
        $this->mockWebApplication([
            'components' => [
                'urlManager' => [
                    'enablePrettyUrl' => true,
                    'enableStrictParsing' => true,
                    'showScriptName' => false,
                    'rules' => [
                        'GET mypath' => 'mycontroller/myaction',
                        'GET mypath2/<myparam>' => 'mycontroller2/myaction2'
                    ],
                ]
            ]
        ]);
        $view = $this->mockView();
        $this->assertEquals('/mypath?myparam=123', $view->renderFile('@yiiunit/twig/views/path/pathWithParams.twig'));//bc
        $this->assertEquals('/mypath2/123', $view->renderFile('@yiiunit/twig/views/path/path2WithParams.twig'));//bc
        $this->assertEquals('/some/custom/path', $view->renderFile('@yiiunit/twig/views/path/pathCustom.twig'));

        //to resolve url as a route first arg should be an array
        $this->assertEquals('/mycontroller/myaction', $view->renderFile('@yiiunit/twig/views/path/pathWithoutParams.twig'));

        $this->assertEquals('/mypath', $view->renderFile('@yiiunit/twig/views/path/pathWithoutParamsAsArray.twig'));
        $this->assertEquals('/mypath?myparam=123', $view->renderFile('@yiiunit/twig/views/path/pathWithParamsAsArray.twig'));
        $this->assertEquals('/mypath2/123', $view->renderFile('@yiiunit/twig/views/path/path2WithParamsAsArray.twig'));
    }

    public function testUrl()
    {
        $this->mockWebApplication([
            'components' => [
                'urlManager' => [
                    'enablePrettyUrl' => true,
                    'enableStrictParsing' => true,
                    'showScriptName' => false,
                    'rules' => [
                        'GET mypath' => 'mycontroller/myaction',
                        'GET mypath2/<myparam>' => 'mycontroller2/myaction2'
                    ],
                ]
            ]
        ]);

        Yii::$app->request->setHostInfo('http://testurl.com');
        $view = $this->mockView();
        $this->assertEquals('http://testurl.com/mypath?myparam=123', $view->renderFile('@yiiunit/twig/views/url/urlWithParams.twig'));//bc
        $this->assertEquals('http://testurl.com/mypath2/123', $view->renderFile('@yiiunit/twig/views/url/url2WithParams.twig'));//bc
        $this->assertEquals('http://testurl.com/some/custom/path', $view->renderFile('@yiiunit/twig/views/url/urlCustom.twig'));

        //to resolve url as a route first arg should be an array
        $this->assertEquals('http://testurl.com/mycontroller/myaction', $view->renderFile('@yiiunit/twig/views/url/urlWithoutParams.twig'));

        $this->assertEquals('http://testurl.com/mypath', $view->renderFile('@yiiunit/twig/views/url/urlWithoutParamsAsArray.twig'));
        $this->assertEquals('http://testurl.com/mypath?myparam=123', $view->renderFile('@yiiunit/twig/views/url/urlWithParamsAsArray.twig'));
        $this->assertEquals('http://testurl.com/mypath2/123', $view->renderFile('@yiiunit/twig/views/url/url2WithParamsAsArray.twig'));
    }

    public function testStaticAndConsts()
    {
        $view = $this->mockView();
        $view->renderers['twig']['globals']['staticClass'] = ['class' => \yiiunit\twig\data\StaticAndConsts::class];
        $content = $view->renderFile('@yiiunit/twig/views/staticAndConsts.twig');
        $this->assertContains('I am a const!', $content);
        $this->assertContains('I am a static var!', $content);
        $this->assertContains('I am a static function with param pam-param!', $content);
    }

    public function testDate()
    {
        $view = $this->mockView();
        $date = new \DateTime();
        $content = $view->renderFile('@yiiunit/twig/views/date.twig', compact('date'));
        $this->assertEquals($content, $date->format('Y-m-d'));
    }


    /**
     * Mocks view instance
     * @return View
     */
    protected function mockView()
    {
        return new View([
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'options' => [
                        'cache' => false,
                    ],
                    'globals' => [
                        'pos_begin' => View::POS_BEGIN,
                    ],
                    'functions' => [
                        't' => '\Yii::t',
                        'json_encode' => '\yii\helpers\Json::encode',
                        new \Twig_SimpleFunction('rot13', 'str_rot13'),
                        new \Twig_SimpleFunction('add_*', function ($symbols, $val) {
                            return $val . $symbols;
                        }, ['is_safe' => ['html']]),
                        'callable_rot13' => function($string) {
                            return str_rot13($string);
                        },
                        'callable_add_*' => function ($symbols, $val) {
                            return $val . $symbols;
                        },
                        'callable_sum' => function ($a, $b) {
                            return $a + $b;
                        }
                    ],
                    'filters' => [
                        'string_rot13' => 'str_rot13',
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
                    'lexerOptions' => [
                        'tag_comment' => [ '{*', '*}' ],
                    ],
                    'extensions' => [
                        '\yii\twig\html\HtmlHelperExtension'
                    ]
                ],
            ],
            'assetManager' => $this->mockAssetManager(),
        ]);
    }

    /**
     * Mocks asset manager
     * @return AssetManager
     */
    protected function mockAssetManager()
    {
        $assetDir = Yii::getAlias('@runtime/assets');
        if (!is_dir($assetDir)) {
            mkdir($assetDir, 0777, true);
        }

        return new AssetManager([
            'basePath' => $assetDir,
            'baseUrl' => '/assets',
        ]);
    }
}
