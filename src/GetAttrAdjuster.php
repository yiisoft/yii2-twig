<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig;

use Twig\Environment;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Node;
use Twig\NodeVisitor\NodeVisitorInterface;

/**
 * GetAttrAdjuster swaps Twig_Node_Expression_GetAttr nodes with [[GetAttr]] nodes.
 */
class GetAttrAdjuster implements NodeVisitorInterface
{
    /**
     * @inheritdoc
     */
    public function enterNode(Node $node, Environment $env): Node
    {
        // Is it a Twig_Node_Expression_GetAttr (and not a subclass)?
        if (get_class($node) === GetAttrExpression::class) {
            // "Clone" it into a GetAttr node
            $nodes = [
                'node' => $node->getNode('node'),
                'attribute' => $node->getNode('attribute')
            ];
            if ($node->hasNode('arguments')) {
                $nodes['arguments'] = $node->getNode('arguments');
            }
            $attributes = [
                'type' => $node->getAttribute('type'),
                'is_defined_test' => $node->getAttribute('is_defined_test'),
                'ignore_strict_check' => $node->getAttribute('ignore_strict_check')
            ];
            $node = new GetAttr($nodes, $attributes, $node->getTemplateLine(), $node->getNodeTag());
        }
        return $node;
    }

    /**
     * @inheritdoc
     */
    public function leaveNode(Node $node, Environment $env): ?Node
    {
        return $node;
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return 0;
    }
}
