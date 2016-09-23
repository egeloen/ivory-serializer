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
 * @Target({"CLASS"})
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class Order
{
    /**
     * @var string|string[]
     */
    private $order;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->order = isset($data['value']) ? $data['value'] : null;
    }

    /**
     * @return string|string[]
     */
    public function getOrder()
    {
        return $this->order;
    }
}
