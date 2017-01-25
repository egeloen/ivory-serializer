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

use Doctrine\Common\Lexer\AbstractLexer;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class TypeLexer extends AbstractLexer
{
    const T_NONE = 1;
    const T_NAME = 2;
    const T_STRING = 3;
    const T_GREATER_THAN = 4;
    const T_LOWER_THAN = 5;
    const T_COMMA = 6;
    const T_EQUAL = 7;

    /**
     * {@inheritdoc}
     */
    protected function getCatchablePatterns()
    {
        return [
            '\'(?:[^\']|\'\')*\'',
            '([a-z0-9\\\\]+)',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getNonCatchablePatterns()
    {
        return [
            '\s+',
            '(.)',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getType(&$value)
    {
        if (ctype_alpha($value[0])) {
            return self::T_NAME;
        }

        if ($value[0] === '\'') {
            $value = str_replace('\'\'', '\'', substr($value, 1, -1));

            return self::T_STRING;
        }

        if ($value === '>') {
            return self::T_GREATER_THAN;
        }

        if ($value === '<') {
            return self::T_LOWER_THAN;
        }

        if ($value === ',') {
            return self::T_COMMA;
        }

        if ($value === '=') {
            return self::T_EQUAL;
        }

        return self::T_NONE;
    }
}
