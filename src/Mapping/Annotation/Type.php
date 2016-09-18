<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class Type
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->type = isset($data['value']) ? $data['value'] : null;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}
