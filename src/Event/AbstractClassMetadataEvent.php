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
use Symfony\Component\EventDispatcher\Event;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class AbstractClassMetadataEvent extends Event
{
    /**
     * @var ClassMetadataInterface|null
     */
    protected $classMetadata;

    /**
     * @return ClassMetadataInterface
     */
    public function getClassMetadata()
    {
        return $this->classMetadata;
    }
}
