<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig;

use yii\base\InvalidCallException;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * Extension provides Yii-specific syntax for Twig templates.
 *
 * @author Andrey Grachov <andrey.grachov@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Extension extends \Twig_Extension
{
    /**
     * @var array used namespaces
     */
    protected $namespaces = [];
    /**
     * @var array used class aliases
     */
    protected $aliases = [];
    /**
     * @var array used widgets
     */
    protected $widgets = [];


    /**
     * Creates new instance
     *
     * @param array $uses namespaces and classes to use in the template
     */
    public function __construct(array $uses = [])
    {
        $this->addUses($uses);
    }

    /**
     * @inheritdoc
     */
    public function getNodeVisitors()
    {
        return [
            new Optimizer(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        $options = [
            'is_safe' => ['html'],
        ];
        $functions = [
            new \Twig_SimpleFunction('use', [$this, 'addUses'], $options),
            new \Twig_SimpleFunction('*_begin', [$this, 'beginWidget'], $options),
            new \Twig_SimpleFunction('*_end', [$this, 'endWidget'], $options),
            new \Twig_SimpleFunction('widget_end', [$this, 'endWidget'], $options),
            new \Twig_SimpleFunction('*_widget', [$this, 'widget'], $options),
            new \Twig_SimpleFunction('path', [$this, 'path']),
            new \Twig_SimpleFunction('url', [$this, 'url']),
            new \Twig_SimpleFunction('void', function(){}),
            new \Twig_SimpleFunction('set', [$this, 'setProperty']),
        ];

        $options = array_merge($options, [
            'needs_context' => true,
        ]);
        $functions[] = new \Twig_SimpleFunction('register_*', [$this, 'registerAsset'], $options);
        $functions[] = new \Twig_SimpleFunction('register_asset_bundle', [$this, 'registerAssetBundle'], $options);
        foreach (['begin_page', 'end_page', 'begin_body', 'end_body', 'head'] as $helper) {
            $functions[] = new \Twig_SimpleFunction($helper, [$this, 'viewHelper'], $options);
        }
        return $functions;
    }

    /**
     * Function for registering an asset
     *
     * ```
     * {{ use('yii/web/JqueryAsset') }}
     * {{ register_jquery_asset() }}
     * ```
     *
     * @param array $context context information
     * @param string $asset asset name
     * @return mixed
     */
    public function registerAsset($context, $asset)
    {
        return $this->resolveAndCall($asset, 'register', [
            isset($context['this']) ? $context['this'] : null,
        ]);
    }

    /**
     * Function for additional syntax of registering asset bundles
     *
     * ```
     * {{ register_asset_bundle('yii/web/JqueryAsset') }}
     * ```
     *
     * @param array $context context information
     * @param string $bundle asset bundle class fully qualified name
     *
     * @since 2.0.4
     */
    public function registerAssetBundle($context, $bundle)
    {
        $bundle = str_replace('/', '\\', $bundle);
        $this->call($bundle, 'register', [
            isset($context['this']) ? $context['this'] : null,
        ]);
    }

    /**
     * Function for *_begin syntax support
     *
     * @param string $widget widget name
     * @param array $config widget config
     * @return mixed
     */
    public function beginWidget($widget, $config = [])
    {
        $widget = $this->resolveClassName($widget);
        $this->widgets[] = $widget;
        return $this->call($widget, 'begin', [
            $config,
        ]);
    }

    /**
     * Function for *_end syntax support
     *
     * @param string $widget widget name
     */
    public function endWidget($widget = null)
    {
        if ($widget === null) {
            if (empty($this->widgets)) {
                throw new InvalidCallException('Unexpected end_widget() call. A matching begin_widget() is not found.');
            }
            $this->call(array_pop($this->widgets), 'end');
        } else {
            array_pop($this->widgets);
            $this->resolveAndCall($widget, 'end');
        }
    }

    /**
     * Function for *_widget syntax support
     *
     * @param string $widget widget name
     * @param array $config widget config
     * @return mixed
     */
    public function widget($widget, $config = [])
    {
        return $this->resolveAndCall($widget, 'widget', [
            $config,
        ]);
    }

    public function viewHelper($context, $name = null)
    {
        if ($name !== null && isset($context['this'])) {
            $this->call($context['this'], Inflector::variablize($name));
        }
    }

    /**
     * Resolves a method from widget and asset syntax and calls it
     *
     * @param string $className class name
     * @param string $method method name
     * @param array $arguments
     * @return mixed
     */
    public function resolveAndCall($className, $method, $arguments = null)
    {
        return $this->call($this->resolveClassName($className), $method, $arguments);
    }

    /**
     * Calls a method
     *
     * @param string $className class name
     * @param string $method method name
     * @param array $arguments
     * @return mixed
     */
    public function call($className, $method, $arguments = null)
    {
        $callable = [$className, $method];
        if ($arguments === null) {
            return call_user_func($callable);
        } else {
            return call_user_func_array($callable, $arguments);
        }
    }

    /**
     * Resolves class name from widget and asset syntax
     *
     * @param string $className class name
     * @return string
     */
    public function resolveClassName($className)
    {
        $className = Inflector::id2camel($className, '_');
        if (isset($this->aliases[$className])) {
            return $this->aliases[$className];
        }
        foreach ($this->namespaces as $namespace) {
            $resolvedClassName = $namespace . '\\' . $className;
            if (class_exists($resolvedClassName)) {
                return $this->aliases[$className] = $resolvedClassName;
            }
        }
        return $className;
    }

    /**
     * Adds namespaces and aliases from constructor
     *
     * @param array $args namespaces and classes to use in the template
     */
    public function addUses($args)
    {
        foreach ((array) $args as $key => $value) {
            $value = str_replace('/', '\\', $value);
            if (is_int($key)) {
                // namespace or class import
                if (class_exists($value)) {
                    // class import
                    $this->aliases[StringHelper::basename($value)] = $value;
                } else {
                    // namespace
                    $this->namespaces[] = $value;
                }
            } else {
                // aliased class import
                $this->aliases[$key] = $value;
            }
        }
    }

    /**
     * Generates relative URL
     *
     * @param string $path the parameter to be used to generate a valid URL
     * @param array $args arguments
     * @return string the generated relative URL
     */
    public function path($path, $args = [])
    {
        if ($args !== []) {
            $path = array_merge([$path], $args);
        }
        return Url::to($path);
    }

    /**
     * Generates absolute URL
     *
     * @param string $path the parameter to be used to generate a valid URL
     * @param array $args arguments
     * @return string the generated absolute URL
     */
    public function url($path, $args = [])
    {
        if ($args !== []) {
            $path = array_merge([$path], $args);
        }
        return Url::to($path, true);
    }

    /**
     * Sets object property
     *
     * @param \stdClass $object
     * @param string $property
     * @param mixes $value
     */
    public function setProperty($object, $property, $value)
    {
        $object->$property = $value;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'yii2-twig';
    }
}