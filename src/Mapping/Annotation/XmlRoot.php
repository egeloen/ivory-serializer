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
class XmlRoot
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->name = isset($data['value']) ? $data['value'] : null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
