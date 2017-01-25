<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Type\Parser;

use Ivory\Serializer\Mapping\TypeMetadata;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class TypeParser implements TypeParserInterface
{
    /**
     * @var TypeLexer
     */
    private $lexer;

    /**
     * @param TypeLexer|null $lexer
     */
    public function __construct(TypeLexer $lexer = null)
    {
        $this->lexer = $lexer ?: new TypeLexer();
    }

    /**
     * {@inheritdoc}
     */
    public function parse($type)
    {
        $type = trim($type);

        if (empty($type)) {
            throw new \InvalidArgumentException('The type must be a non empty string.');
        }

        $this->lexer->setInput($type);
        $this->walk();

        try {
            return $this->parseType();
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf('The type "%s" is not valid.', $type), 0, $e);
        }
    }

    /**
     * @return TypeMetadata
     */
    private function parseType()
    {
        return new TypeMetadata($this->parseName(), $this->parseOptions());
    }

    /**
     * @return string
     */
    private function parseName()
    {
        $token = $this->validateToken(TypeLexer::T_NAME);

        return $token['value'];
    }

    /**
     * @return mixed[]
     */
    private function parseOptions()
    {
        $options = [];
        $token = $this->lexer->token;

        if ($token === null
            || $token['type'] === TypeLexer::T_GREATER_THAN
            || $token['type'] === TypeLexer::T_COMMA
        ) {
            return $options;
        }

        $this->validateToken(TypeLexer::T_LOWER_THAN);

        while ($this->lexer->isNextTokenAny([TypeLexer::T_NAME, TypeLexer::T_STRING])) {
            $options[$this->parseOptionName()] = $this->parseOptionValue();
        }

        $this->validateToken(TypeLexer::T_GREATER_THAN);

        return $options;
    }

    /**
     * @return string
     */
    private function parseOptionName()
    {
        $token = $this->lexer->token;
        $this->walk();
        $this->validateToken(TypeLexer::T_EQUAL);

        return $token['value'];
    }

    /**
     * @return TypeMetadataInterface
     */
    private function parseOptionValue()
    {
        $token = $this->lexer->token;

        if ($token['type'] === TypeLexer::T_STRING) {
            $result = $token['value'];
            $this->walk();
        } else {
            $result = $this->parseType();
        }

        if ($this->lexer->token['type'] === TypeLexer::T_COMMA) {
            $this->walk();
        }

        return $result;
    }

    /**
     * @param int $type
     *
     * @return mixed[]
     */
    private function validateToken($type)
    {
        $token = $this->lexer->token;

        if ($token['type'] !== $type) {
            throw new \InvalidArgumentException(sprintf(
                'Expected a token type "%d", got "%d".',
                TypeLexer::T_LOWER_THAN,
                $token['type']
            ));
        }

        $this->walk();

        return $token;
    }

    /**
     * @param int $count
     *
     * @return mixed[]
     */
    private function walk($count = 1)
    {
        $token = $nextToken = $this->lexer->token;

        for ($i = 0; ($i < $count) || ($token === $nextToken); ++$i) {
            $this->lexer->moveNext();
            $nextToken = $this->lexer->token;
        }

        return $nextToken;
    }
}
