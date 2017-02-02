<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Event;

use Ivory\Serializer\Mapping\ClassMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ClassMetadataLoadEvent extends AbstractClassMetadataEvent
{
    /**
     * @param ClassMetadataInterface $classMetadata
     */
    public function __construct(ClassMetadataInterface $classMetadata)
    {
        $this->classMetadata = $classMetadata;
    }
}
