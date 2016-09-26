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
 * @Target({"CLASS", "PROPERTY", "METHOD"})
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class Writable
{
    /**
     * @var bool
     */
    private $writable;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->writable = isset($data['value']) ? $data['value'] : true;
    }

    /**
     * @return bool
     */
    public function isWritable()
    {
        return $this->writable;
    }
}
