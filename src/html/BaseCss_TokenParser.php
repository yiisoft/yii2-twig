<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\twig\html;

use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

abstract class BaseCss_TokenParser extends AbstractTokenParser
{
    public function parse(Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();
        $name = $stream->expect(Token::NAME_TYPE);
        $operator = $stream->expect(Token::OPERATOR_TYPE);
        $value = $parser->getExpressionParser()->parseExpression();
        $stream->expect(Token::BLOCK_END_TYPE);
        $nodeClass = $this->getNodeClass();

        return new $nodeClass($name, $value, $operator, $token->getLine(), $this->getTag());
    }

    abstract public function getNodeClass();
}
