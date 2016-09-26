<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Naming;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class AbstractSeparatorNamingStrategy extends AbstractNamingStrategy
{
    /**
     * @var string
     */
    private $separator;

    /**
     * @param string $separator
     */
    public function __construct($separator)
    {
        $this->separator = $separator;
    }

    /**
     * {@inheritdoc}
     */
    protected function doConvert($name)
    {
        $name = str_replace(['--', '__', '  '], ' ', $name);
        $name = lcfirst(str_replace(['-', '_', ' '], $this->separator, $name));

        return strtolower(preg_replace('/([A-Z])/', $this->separator.'$1', $name));
    }
}
