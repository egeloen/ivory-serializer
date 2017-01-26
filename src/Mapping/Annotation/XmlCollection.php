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
class XmlCollection
{
    /**
     * @var string
     */
    public $entry;

    /**
     * @var string
     */
    public $entryAttribute;

    /**
     * @var bool
     */
    public $keyAsAttribute;

    /**
     * @var bool
     */
    public $keyAsNode;

    /**
     * @var bool
     */
    public $inline;
}
