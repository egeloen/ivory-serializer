<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping\Factory;

use Ivory\Serializer\Mapping\ClassMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractClassMetadataFactory implements ClassMetadataFactoryInterface
{
    /**
     * @var ClassMetadataInterface[]
     */
    private $classMetadatas = [];

    /**
     * {@inheritdoc}
     */
    public function getClassMetadata($class)
    {
        return array_key_exists($class, $this->classMetadatas)
            ? $this->classMetadatas[$class]
            : $this->classMetadatas[$class] = $this->fetchClassMetadata($class);
    }

    /**
     * @param string $class
     *
     * @return ClassMetadataInterface|null
     */
    abstract protected function fetchClassMetadata($class);
}
