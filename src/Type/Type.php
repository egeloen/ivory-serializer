<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Type;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
final class Type
{
    const ARRAY_ = 'array';
    const BOOL = 'bool';
    const BOOLEAN = 'boolean';
    const CLOSURE = \Closure::class;
    const DATE_TIME = \DateTimeInterface::class;
    const DOUBLE = 'double';
    const EXCEPTION = \Exception::class;
    const FLOAT = 'float';
    const INT = 'int';
    const INTEGER = 'integer';
    const NULL = 'null';
    const NUMERIC = 'numeric';
    const OBJECT = 'object';
    const RESOURCE = 'resource';
    const STD_CLASS = \stdClass::class;
    const STRING = 'string';

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }
}
