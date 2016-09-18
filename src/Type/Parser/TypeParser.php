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

use Doctrine\Common\Lexer;
use Ivory\Serializer\Mapping\TypeMetadata;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class TypeParser implements TypeParserInterface
{
    /**
     * @var Lexer
     */
    private $lexer;

    /**
     * @param Lexer|null $lexer
     */
    public function __construct(Lexer $lexer = null)
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

        $lexer = clone $this->lexer;
        $lexer->setInput($type);
        $this->walk($lexer);

        try {
            return $this->doParse($lexer);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf('The type "%s" is not valid.', $type), 0, $e);
        }
    }

    /**
     * @param Lexer $lexer
     *
     * @return TypeMetadata
     */
    private function doParse(Lexer $lexer)
    {
        return new TypeMetadata($this->getName($lexer), $this->getOptions($lexer));
    }

    /**
     * @param Lexer $lexer
     *
     * @return string
     */
    private function getName(Lexer $lexer)
    {
        $token = $this->validate($lexer, TypeLexer::T_NAME);

        return $token['value'];
    }

    /**
     * @param Lexer $lexer
     *
     * @return mixed[]
     */
    private function getOptions(Lexer $lexer)
    {
        $options = [];
        $token = $lexer->token;

        if ($token === null
            || $token['type'] === TypeLexer::T_GREATER_THAN
            || $token['type'] === TypeLexer::T_COMMA
        ) {
            return $options;
        }

        $this->validate($lexer, TypeLexer::T_LOWER_THAN);

        while ($lexer->isNextTokenAny([TypeLexer::T_NAME, TypeLexer::T_STRING])) {
            $options[$this->getOptionName($lexer)] = $this->getOptionValue($lexer);
        }

        $this->validate($lexer, TypeLexer::T_GREATER_THAN);

        return $options;
    }

    /**
     * @param Lexer $lexer
     *
     * @return string
     */
    private function getOptionName(Lexer $lexer)
    {
        $token = $lexer->token;
        $this->walk($lexer);
        $this->validate($lexer, TypeLexer::T_EQUAL);

        return $token['value'];
    }

    /**
     * @param Lexer $lexer
     *
     * @return TypeMetadataInterface
     */
    private function getOptionValue(Lexer $lexer)
    {
        $token = $lexer->token;

        if ($token['type'] === TypeLexer::T_STRING) {
            $result = $token['value'];
            $this->walk($lexer);
        } else {
            $result = $this->doParse($lexer);
        }

        if ($lexer->token['type'] === TypeLexer::T_COMMA) {
            $this->walk($lexer);
        }

        return $result;
    }

    /**
     * @param Lexer $lexer
     * @param int   $type
     *
     * @return mixed[]
     */
    private function validate(Lexer $lexer, $type)
    {
        $token = $lexer->token;

        if ($token['type'] !== $type) {
            throw new \InvalidArgumentException(sprintf(
                'Expected a token type "%d", got "%d".',
                TypeLexer::T_LOWER_THAN,
                $token['type']
            ));
        }

        $this->walk($lexer);

        return $token;
    }

    /**
     * @param Lexer $lexer
     * @param int   $count
     *
     * @return mixed[]
     */
    private function walk(Lexer $lexer, $count = 1)
    {
        $token = $nextToken = $lexer->token;

        for ($i = 0; ($i < $count) || ($token === $nextToken); ++$i) {
            $lexer->moveNext();
            $nextToken = $lexer->token;
        }

        return $nextToken;
    }
}
