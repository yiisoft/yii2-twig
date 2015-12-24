<?php
namespace yiiunit\extensions\twig;

use yii\helpers\FileHelper;
use yii\web\AssetManager;
use yii\web\View;
use Yii;
use yiiunit\extensions\twig\data\Order;
use yiiunit\extensions\twig\data\Singer;

/**
 * Tests Twig view renderer
 *
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @author Carsten Brandt <mail@cebe.cc>
 */
class ViewRendererTest extends TestCase
{
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
        $content = $view->renderFile('@yiiunit/extensions/twig/views/layout.twig');

        $this->assertEquals(1, preg_match('#<script src="/assets/[0-9a-z]+/jquery\\.js"></script>\s*</body>#', $content), 'Content does not contain the jquery js:' . $content);
    }

    public function testAppGlobal()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/extensions/twig/views/layout.twig');

        $this->assertEquals(1, preg_match('#<meta charset="' . Yii::$app->charset . '"/>#', $content), 'Content does not contain charset:' . $content);
    }

    /**
     * https://github.com/yiisoft/yii2/issues/3877
     */
    public function testLexerOptions()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/extensions/twig/views/comments.twig');

        $this->assertFalse(strpos($content, 'CUSTOM_LEXER_TWIG_COMMENT'), 'Custom comment lexerOptions were not applied: ' . $content);
        $this->assertTrue(strpos($content, 'DEFAULT_TWIG_COMMENT') !== false, 'Default comment style was not modified via lexerOptions:' . $content);
    }

    public function testForm()
    {
        $view = $this->mockView();
        $model = new Singer();
        $content = $view->renderFile('@yiiunit/extensions/twig/views/form.twig', ['model' => $model]);
        $this->assertEquals(1, preg_match('#<form id="login-form" class="form-horizontal" action="/form-handler" method="post">.*?</form>#s', $content), 'Content does not contain form:' . $content);
    }

    public function testCalls()
    {
        $view = $this->mockView();
        $model = new Singer();
        $content = $view->renderFile('@yiiunit/extensions/twig/views/calls.twig', ['model' => $model]);
        $this->assertFalse(strpos($content, 'silence'), 'silence should not be echoed when void() used: ' . $content);
        $this->assertTrue(strpos($content, 'echo') !== false, 'echo should be there:' . $content);
        $this->assertTrue(strpos($content, 'variable') !== false, 'variable should be there:' . $content);
    }

    public function testInheritance()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/extensions/twig/views/extends2.twig');
        $this->assertTrue(strpos($content, 'Hello, I\'m inheritance test!') !== false, 'Hello, I\'m inheritance test! should be there:' . $content);
        $this->assertTrue(strpos($content, 'extends2 block') !== false, 'extends2 block should be there:' . $content);
        $this->assertFalse(strpos($content, 'extends1 block') !== false, 'extends1 block should not be there:' . $content);

        $content = $view->renderFile('@yiiunit/extensions/twig/views/extends3.twig');
        $this->assertTrue(strpos($content, 'Hello, I\'m inheritance test!') !== false, 'Hello, I\'m inheritance test! should be there:' . $content);
        $this->assertTrue(strpos($content, 'extends3 block') !== false, 'extends3 block should be there:' . $content);
        $this->assertFalse(strpos($content, 'extends1 block') !== false, 'extends1 block should not be there:' . $content);
    }

    public function testChangeTitle()
    {
        $view = $this->mockView();
        $view->title = 'Original title';

        $content = $view->renderFile('@yiiunit/extensions/twig/views/changeTitle.twig');
        $this->assertTrue(strpos($content, 'New title') !== false, 'New title should be there:' . $content);
        $this->assertFalse(strpos($content, 'Original title') !== false, 'Original title should not be there:' . $content);
    }

    public function testNullsInAr()
    {
        Order::setUp();

        $view = $this->mockView();
        $order = new Order();
        $view->renderFile('@yiiunit/extensions/twig/views/nulls.twig', ['order' => $order]);
    }

    public function testSimpleFilters()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/extensions/twig/views/simpleFilters1.twig');
        $this->assertEquals($content, 'Gjvt');
        $content = $view->renderFile('@yiiunit/extensions/twig/views/simpleFilters2.twig');
        $this->assertEquals($content, 'val42');
        $content = $view->renderFile('@yiiunit/extensions/twig/views/simpleFilters3.twig');
        $this->assertEquals($content, 'Gjvt');
        $content = $view->renderFile('@yiiunit/extensions/twig/views/simpleFilters4.twig');
        $this->assertEquals($content, 'val42');
        $content = $view->renderFile('@yiiunit/extensions/twig/views/simpleFilters5.twig');
        $this->assertEquals($content, 'Gjvt');
    }

    public function testSimpleFunctions()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/extensions/twig/views/simpleFunctions1.twig');
        $this->assertEquals($content, 'Gjvt');
        $content = $view->renderFile('@yiiunit/extensions/twig/views/simpleFunctions2.twig');
        $this->assertEquals($content, 'val43');
        $content = $view->renderFile('@yiiunit/extensions/twig/views/simpleFunctions3.twig');
        $this->assertEquals($content, 'Gjvt');
        $content = $view->renderFile('@yiiunit/extensions/twig/views/simpleFunctions4.twig');
        $this->assertEquals($content, 'val43');
        $content = $view->renderFile('@yiiunit/extensions/twig/views/simpleFunctions5.twig');
        $this->assertEquals($content, '6');
    }

    public function testRegisterAssetBundle()
    {
        $view = $this->mockView();
        $content = $view->renderFile('@yiiunit/extensions/twig/views/register_asset_bundle.twig');
        $this->assertEquals(Yii::getAlias('@bower/jquery/dist'), $content);
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
                        'html' => '\yii\helpers\Html',
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
