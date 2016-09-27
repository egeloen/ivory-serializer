<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
final class Format
{
    const CSV = 'csv';
    const JSON = 'json';
    const XML = 'xml';
    const YAML = 'yaml';

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }
}
