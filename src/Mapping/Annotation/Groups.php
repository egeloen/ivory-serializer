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
class Groups
{
    /**
     * @var string[]
     */
    private $groups;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->groups = isset($data['value']) ? $data['value'] : [];
    }

    /**
     * @return string[]
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
