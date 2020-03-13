<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig;

use Twig\Environment;
use Twig\Node\DoNode;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\FunctionExpression;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\NodeVisitor\NodeVisitorInterface;

/**
 * Optimizer removes echo before special functions call and injects function name as an argument for the view helper
 * calls.
 *
 * @author Andrey Grachov <andrey.grachov@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Optimizer implements NodeVisitorInterface
{
    /**
     * @inheritdoc
     */
    public function enterNode(Node $node, Environment $env): Node
    {
        return $node;
    }

    /**
     * @inheritdoc
     */
    public function leaveNode(Node $node, Environment $env): ?Node
    {
        if ($node instanceof PrintNode) {
            $expression = $node->getNode('expr');
            if ($expression instanceof FunctionExpression) {
                $name = $expression->getAttribute('name');
                if (preg_match('/^(?:register_.+_asset|use|.+_begin|.+_end)$/', $name)) {
                    return new DoNode($expression, $expression->getTemplateLine());
                } elseif (in_array($name, ['begin_page', 'end_page', 'begin_body', 'end_body', 'head'])) {
                    $arguments = [
                        new ConstantExpression($name, $expression->getTemplateLine()),
                    ];
                    if ($expression->hasNode('arguments') && $expression->getNode('arguments') !== null) {
                        foreach ($expression->getNode('arguments') as $key => $value) {
                            if (is_int($key)) {
                                $arguments[] = $value;
                            } else {
                                $arguments[$key] = $value;
                            }
                        }
                    }
                    $expression->setNode('arguments', new Node($arguments));
                    return new DoNode($expression, $expression->getTemplateLine());
                }
            }
        }
        return $node;
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 100;
    }
}
